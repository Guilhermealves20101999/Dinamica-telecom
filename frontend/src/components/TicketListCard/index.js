import React, { useState, useEffect, useRef, useContext } from "react";

import { useHistory, useParams } from "react-router-dom";
import { parseISO, format, isSameDay } from "date-fns";
import clsx from "clsx";

import { makeStyles } from "@material-ui/core/styles";
import { green } from "@material-ui/core/colors";
import Typography from "@material-ui/core/Typography";
import Avatar from "@material-ui/core/Avatar";
import Badge from "@material-ui/core/Badge";

import { i18n } from "../../translate/i18n";

import InsertEmoticonIcon from '@material-ui/icons/InsertEmoticon';
import SentimentSatisfiedIcon from '@material-ui/icons/SentimentSatisfied';
import SentimentVeryDissatisfiedIcon from '@material-ui/icons/SentimentVeryDissatisfied';

import api from "../../services/api";
import ButtonWithSpinner from "../ButtonWithSpinner";
import MarkdownWrapper from "../MarkdownWrapper";
import { Grid } from "@material-ui/core";
import { AuthContext } from "../../context/Auth/AuthContext";

import Tag from "../Tag";
import ServiceIcon from "../ServiceIcon";
import { toast } from "react-toastify";
import toastError from "../../errors/toastError";

const useStyles = makeStyles((theme) => ({
    ticket: {
        position: "relative",
        backgroundColor: "#fafafa",
        padding: theme.spacing(2),
        borderRadius: "5px",
        boxShadow: "0 0 0.3em rgba(0,0,0,0.2)",
        marginBottom: theme.spacing(1),
        width: "100%",
        cursor: "pointer",
        "&:hover": {
            backgroundColor: "#e6e6e6",
        },
    },

    pendingTicket: {
        cursor: "unset",
    },

    contactNameWrapper: {
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
    },

    lastMessageTime: {
        justifySelf: "flex-end",
        marginLeft: "5px",
    },

    closedBadge: {
        position: "relative",
        marginLeft: "auto",
        marginRight: "auto",
        display: "block"
    },

    contactLastMessage: {
        paddingRight: 20,
    },

    newMessagesCount: {
        display: "flex",
        marginTop: 10,
        justifyContent: "flex-end",
        width: "20%",
    },

    badgeStyle: {
        color: "white",
        backgroundColor: green[500],
    },

    acceptButton: {
        display: "flex",
        width: "100%",
        marginRight: "10px",
    },

    ticketQueueColor: {
        flex: "none",
        width: "8px",
        height: "100%",
        position: "absolute",
        top: "0%",
        left: "0%",
    },

    icon: {
        width: "50px",
        height: "50px",
    },

    time: {
        display: "flex",
        justifyContent: "flex-end",
        width: "100%",
        // marginRight: "10px",
    },

    containMessage: {
        display: "flex",
        flexDirection: "column",
        justifyContent: "center",
    },

    tags: {
        display: "flex",
        flexDirection: "column",
    },

    selected: {
        border: "1px solid #51127f",
    },

    avatarWraper: {
        height: 50,
        width: 50,
        marginRight: 16,
        position: "relative",
    },

    serviceIconWrapper: {
        borderRadius: "50%",
        width: 20,
        height: 20,
        textAlign: "center",
        background: "#fff",
        position: "absolute",
        bottom: 0,
        right: 0,
    },
}));

const TicketListCard = ({ ticket, showAcceptButton }) => {
    const classes = useStyles();
    const history = useHistory();
    const [loading, setLoading] = useState(false);
    const { ticketId } = useParams();
    const isMounted = useRef(true);
    const { user } = useContext(AuthContext);
    const permission = user.roles.find((role) => role === "tickets:openPending");

    useEffect(() => {
        return () => {
            isMounted.current = false;
        };
    }, []);

    const handleAcepptTicket = async (id) => {
        setLoading(true);
        try {
            await api.put(`/tickets/${id}`, {
                status: "open",
                userId: user?.id,
            });
        } catch (err) {
            setLoading(false);
            if (err.response?.status !== 500) {
                toastError(err);
            } else {
                toast.error(`${i18n.t("frontEndErrors.acceptTicket")}`);
            }
        }
        if (isMounted.current) {
            setLoading(false);
        }
        history.push(`/tickets/${id}`);
    };

    const handleSelectTicket = (id) => {
        if (ticket.status == 'pending' && !permission) {
            if (user.profileSlug === 'super') {
                history.push(`/tickets/${id}`)
            }else {
                return
            }
        } else {
            history.push(`/tickets/${id}`)
        }
    };

    return (
        <React.Fragment key={ticket.id}>
            <Grid
                container
                onClick={(e) => {
                    handleSelectTicket(ticket.id);
                }}
                selected={ticketId && +ticketId === ticket.id}
                className={clsx(classes.ticket, {
                    [classes.pendingTicket]: ticket.status === "pending",
                    [classes.selected]: +ticketId === ticket.id,
                })}
                style={{
                    borderLeft: ticket.queue ? `5px solid ${ticket.queue.color}` : "5px solid #e6e6e6",
                }}
            >
                <Grid item xs={2} className={classes.containMessage}>
                    <div className={classes.avatarWraper}>
                        <Avatar className={classes.icon} src={ticket?.contact?.profilePicUrl}>
                            {ticket.contact?.name[0]}
                        </Avatar>
                        {ticket.status !== "internal" && ticket.whatsapp ? (
                            <div className={classes.serviceIconWrapper}>
                                <ServiceIcon type={ticket?.whatsapp.type} />
                            </div>
                        ) : null}
                    </div>
                </Grid>

                <Grid item xs={7} className={classes.containMessage}>
                    <span className={classes.contactNameWrapper}>
                        <Typography noWrap component="span" variant="body2" color="textPrimary">
                            {ticket.kind === "chat"
                                ? ticket.contact?.name
                                : `${ticket.transmission?.name} (${ticket.transmission?.contacts?.length})`}
                        </Typography>
                    </span>

                    <span className={classes.contactNameWrapper}>
                        <Typography
                            className={classes.contactLastMessage}
                            noWrap
                            component="span"
                            variant="body2"
                            color="textSecondary"
                        >
                            {ticket.lastMessage ? <MarkdownWrapper>{ticket.lastMessage}</MarkdownWrapper> : <br />}
                        </Typography>
                    </span>
                </Grid>
                <Grid item xs={1} className={classes.containIcon}>
                    <div className={classes.tags}>
                        {ticket.contact?.tags?.map((tag) =>
                            tag.priority > 0 ? <Tag key={tag.id} priority={tag.priority} color={tag.color} /> : null
                        )}
                    </div>
                </Grid>
                <Grid item xs={2} className={classes.containIcon}>
                    <div className={classes.time}>
                        <Typography
                            className={classes.lastMessageTime}
                            component="span"
                            variant="body2"
                            color="textSecondary"
                        >
                            {isSameDay(parseISO(ticket.updatedAt), new Date()) ? (
                                <>{format(parseISO(ticket.updatedAt), "HH:mm")}</>
                            ) : (
                                <>{format(parseISO(ticket.updatedAt), "dd/MM/yyyy")}</>
                            )}
                        </Typography>

                        {(ticket.userId === user.id && ticket.unreadMessages > 0) ||
                            (ticket.destinationUserId === user.id && ticket.unreadMessages2 > 0) ? (
                            <Badge
                                className={classes.newMessagesCount}
                                badgeContent={
                                    ticket.userId === user.id ? ticket.unreadMessages : ticket.unreadMessages2
                                }
                                classes={{
                                    badge: classes.badgeStyle,
                                }}
                            />
                        ) : null}
                    </div>

                    <div className={classes.closedBadge}>
                        {ticket.status === "closed" && ticket.npsValue !== null && ticket.npsValue < 7 ? (
                            <SentimentVeryDissatisfiedIcon style={{ color: '#ba000d' }} />
                        ) : null}
                        {ticket.status === "closed" && ticket.npsValue !== null && ticket.npsValue >= 7 && ticket.npsValue < 9 ? (
                            <SentimentSatisfiedIcon style={{ color: '#ffc400' }} />
                        ) : null}
                        {ticket.status === "closed" && ticket.npsValue !== null && ticket.npsValue > 8 ? (
                            <InsertEmoticonIcon style={{ color: green[500] }} />
                        ) : null}
                    </div>
                    {/*   {ticket.status === "closed" && (
                        <Badge className={classes.closedBadge} badgeContent={"Encerrado"} color="primary" />
                    )}
 */}
                    {ticket.status === "pending" && showAcceptButton && user.isManager !== 1 && (
                        <ButtonWithSpinner
                            color="primary"
                            variant="contained"
                            className={classes.acceptButton}
                            size="small"
                            loading={loading}
                            onClick={(e) => handleAcepptTicket(ticket.id)}
                        >
                            {i18n.t("ticketsList.buttons.accept")}
                        </ButtonWithSpinner>
                    )}
                </Grid>
            </Grid>
        </React.Fragment>
    );
};

export default TicketListCard;
