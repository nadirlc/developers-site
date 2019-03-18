"use strict";

/** The EventHandlers class */
class EventHandlers {

    /** The constructor */
    constructor(contact) {
        this.contact = contact;
    }
        
    /** Used to register the event handlers */
    RegisterEventHandlers() {
        /** The submit button click handler is registered */
        document.getElementById("contact-button").addEventListener("click", () => {this.contact.SendMessage();});
    }        
}

/** The Contact class */
class Contact {

    /** The constructor */
    constructor() {
        /** The event handlers object is created */
        this.event_handlers = new EventHandlers(this);
    }

    /** Used to initialize the contact form */
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
    
    /** Used to send a contact message */
    SendMessage() {    

        /** If the email is not valid */
        if (!document.getElementById("email").checkValidity()) {
            /** An alert message is shown */
            alert("Please enter a valid email address !");
            /** The function returns */
            return;
        }        
        /** If the name was not entered */
        else if (!document.getElementById("name").checkValidity()) {
            /** An alert message is shown */
            alert("Please enter your name !");
            /** The function returns */
            return;
        }
        /** If the message was not entered */
        else if (!document.getElementById("message").checkValidity()) {
            /** An alert message is shown */
            alert("Please enter your message !");
            /** The function returns */
            return;
        }

        /** The email address */
        var email_address     = document.getElementById("email").value;
        /** The name */
        var name              = document.getElementById("name").value;
        /** The messsage */
        var text              = document.getElementById("message").value;
        /** The url used to make the request */
        var url               = "/contact/add";      
        /** The parameters for the request */
        var parameters        = {"email": email_address, "name": name, "message": text};
        
        /** The callback for displaying the confirmation message */
        let success    = (response) => {
            /** The response is json decoded */
            response   = JSON.parse(response);
            /** An alert message is shown */
            alert(response.message);
        };          

        /** The data is sent to server */
        this.MakeRequest(url, parameters, success);
    }
}

/** The Contact object is created */
export let contact = new Contact();
/** The Contact object is initialized */
contact.Initialize();
