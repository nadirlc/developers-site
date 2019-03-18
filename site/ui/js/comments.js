"use strict";

/** The EventHandlers class */
class EventHandlers {

    /** The constructor */
    constructor(comment) {
        this.comment = comment;
    }
        
    /** Used to register the event handlers */
    RegisterEventHandlers() {
        /** The submit button click handler is registered */
        document.getElementById("comment-button").addEventListener("click", () => {this.comment.SendMessage();});
    }        
}

/** The Comment class */
class Comment {

    /** The constructor */
    constructor() {
        /** The event handlers object is created */
        this.event_handlers = new EventHandlers(this);
    }

    /** Used to initialize the comment form */
    Initialize() {    
        /** The event handlers are registered */
        this.event_handlers.RegisterEventHandlers();   
    }
    
    /** Used to make ajax request to the server */
    MakeRequest(url, parameters, success) {
        
        /** The Ajax call */
        $.ajax({
            method: "POST",
            url: url,
            data: parameters,
            async: false
		})
        /** This function is called after data has been loaded */
        .done(success)
        .fail(this.Fail);                
    }
    
    /** Used to indicate that ajax request has failed */
    Fail() {
        alert("Error!");
    }
    
    /** Used to send a comment message */
    SendMessage() {    
        
        /** If the name was not entered */
        if (!document.getElementById("name").checkValidity()) {
            /** An alert message is shown */
            alert("Please enter your name !");
            /** The function returns */
            return;
        }
        /** If the comment was not entered */
        else if (!document.getElementById("message").checkValidity()) {
            /** An alert message is shown */
            alert("Please enter your message !");
            /** The function returns */
            return;
        }

        /** The name */
        var name              = document.getElementById("name").value;
        /** The messsage */
        var text              = document.getElementById("message").value;
        /** The url used to make the request */
        var url               = "/comment/add";      
        /** The url of the current page */
        var page_url          = location.href;
        
        /** The parameters for the request */
        var parameters        = {"name": name, "message": text, "url": page_url};
        
        /** The callback for displaying the confirmation message */
        let success    = (response) => {
            /** The response is json decoded */
            response   = JSON.parse(response);
            /** The comment was successfully posted */
            this.PostComment(response.name, response.comment, response.posted_on);
            /** An alert message is shown */
            alert(response.message);
        };          

        /** The data is sent to server */
        this.MakeRequest(url, parameters, success);
    }
    
    /** Used to add a comment */
    PostComment(name, message, posted_on) {    
        /** The comment template html is read */
        var comment_html  = document.getElementById("comment-template").innerHTML;
        /** The name is added to the comment */
        comment_html      = comment_html.replace("[name]", name);
        /** The message is added to the comment */
        comment_html      = comment_html.replace("[message]", message);
        /** The date is added to the comment */
        comment_html      = comment_html.replace("[date]", posted_on);
        /** The comment object is created */
        var comment       = document.createElement("div");
        /** The contents of the comment node are set */
        comment.innerHTML = comment_html;
        /** The first comment */
        var first_comment = document.getElementById("user-comments").firstChild;
        /** The comment is appended to the list of comments */
        document.getElementById("user-comments").insertBefore(comment, first_comment);
    }
}

/** The Comment object is created */
export let comment = new Comment();
/** The Comment object is initialized */
comment.Initialize();
