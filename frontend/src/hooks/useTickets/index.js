import { useState, useEffect } from "react";
import { getHoursCloseTicketsAuto } from "../../config";
import toastError from "../../errors/toastError";

import api from "../../services/api";

let useTickets = ({
    searchParam,
    pageNumber,
    status,
    userFilter,
    queueFilter,
    date,
    showAll,
    queueIds,
    withUnreadMessages,
    tags,
}) => {

    
    const [loading, setLoading] = useState(true);
    const [hasMore, setHasMore] = useState(false);
    const [tickets, setTickets] = useState([]);
    const [count, setCount] = useState(0);


    useEffect(() => {
        setLoading(true);
        const delayDebounceFn = setTimeout(() => {
            const fetchTickets = async () => {
                try {
                    const { data } = await api.get("/tickets", {
                        params: {
                            searchParam,
                            pageNumber,
                            status,
                            date,
                            showAll,
                            queueIds,
                            withUnreadMessages,
                            tags,
                        },
                    })
                    let tickets = [];

                    if (queueIds) {
                        let queueIdsJson = JSON.parse(queueIds);
                        const ticketsSemfila = queueIdsJson.filter(item => item === 0)
                        if (!ticketsSemfila || ticketsSemfila.length === 0) {
                            tickets = data.tickets.filter(item => item.queueId != null)
                        } else {
                            tickets = data.tickets;
                        }
                    } else {
                        tickets = data.tickets;
                    }
                    if (!!userFilter && !queueFilter) {
                        tickets = tickets.filter(item => item.userId === userFilter.id);
                    } else if (!!queueFilter && !userFilter) {
                        if (queueFilter.id === 5) {
                            tickets = tickets.filter(item => item.queueId === null);
                        } else {
                            tickets = tickets.filter(item => item.queueId === queueFilter.id);
                        }
                    }
                    setTickets(tickets)

                    let horasFecharAutomaticamente = getHoursCloseTicketsAuto();

                    if (status === "open" && horasFecharAutomaticamente && horasFecharAutomaticamente !== "" &&
                        horasFecharAutomaticamente !== "0" && Number(horasFecharAutomaticamente) > 0) {

                        let dataLimite = new Date()
                        dataLimite.setHours(dataLimite.getHours() - Number(horasFecharAutomaticamente))

                        data.tickets.forEach(ticket => {
                            if (ticket.status !== "closed") {
                                let dataUltimaInteracaoChamado = new Date(ticket.updatedAt)
                                if (dataUltimaInteracaoChamado < dataLimite)
                                    closeTicket(ticket)
                            }
                        })
                    }

                    setHasMore(data.hasMore)
                    setCount(data.count)
                    setLoading(false)
                } catch (err) {
                    setLoading(false)
                    toastError(err)
                }
            }

            const closeTicket = async (ticket) => {
                await api.put(`/tickets/${ticket.id}`, {
                    status: "closed",
                    userId: ticket.userId || null,
                })
            }

            fetchTickets()
        }, 500)
        return () => clearTimeout(delayDebounceFn)
    }, [
        userFilter,
        queueFilter,
        searchParam,
        pageNumber,
        status,
        date,
        showAll,
        queueIds,
        withUnreadMessages,
        tags,
    ])

    return { tickets, loading, hasMore, count };
};

export default useTickets;