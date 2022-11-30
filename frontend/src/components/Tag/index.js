import { makeStyles } from "@material-ui/core";
import React from "react";

const useStyles = makeStyles((theme) => ({
    screen: {
        width: "0px"
    },
    roundButton: {
        minHeight: "10px",
        minWidth: "10px",
        borderRadius: 100,
        border: 0,
    },
}));

export default function Tag(props) {
    const classes = useStyles();

    return (
        <div className={classes.screen}>
            <button
                className={classes.roundButton}
                style={{
                    backgroundColor: props.color || "#7C7C7C",
                }}
            >
                {props.name}
            </button>
        </div>
    );
}
