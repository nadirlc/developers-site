"use strict";

/** The SideBar class */
class SideBar {

    /** Used to register the sidebar toggle button handler */
    Initialize() {
        /** When the user scrolls down 200px from the top of the document, show the button */
        window.addEventListener("scroll", this.ToggleButton);
        /** If the height of the sidebar is more than 400px */
        if (document.getElementById("toc").clientHeight > 400) {
            document.getElementById("toc").style.height = "400px";
            document.getElementById("toc").style.overflow = "auto";
        }
    }
    
    /** Displays/Hides the sidebar toggle button */
    ToggleButton() {
        /** If the current current scroll is 200px or more */
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            /** The sidebar toggle button is displayed */
            document.getElementById("toggle-sidebar-btn").style.display = "block";
        } else {
            /** The sidebar toggle button is hidden */
            document.getElementById("toggle-sidebar-btn").style.display = "none";
        }
    }
    
    /** Displays/Hides the side bar */
    ToggleSideBar() {
        /** If the right column has the d-none class, then it is removed */
        if (document.getElementById("right-col").className.indexOf("d-none") >= 0) {
            /** The d-none class is removed */
            document.getElementById("right-col").classList.remove("d-none");
            /** The col-lg-8 class is added to center column */
            document.getElementById("center-col").classList.add("col-lg-8");
            /** The col-lg-12 class is removed from center column */
            document.getElementById("center-col").classList.remove("col-lg-12");
        }
        /** If the right column has the d-none class, then it is removed */
        else {
            /** The d-none class is added */
            document.getElementById("right-col").classList.add("d-none");
            /** The col-lg-8 class is removed from center column */
            document.getElementById("center-col").classList.remove("col-lg-8");
            /** The col-lg-12 class is added to center column */
            document.getElementById("center-col").classList.add("col-lg-12");
        }
    }
}    

var sidebar = "";

window.addEventListener("load", function() {
    /** The SideBar class object */
    sidebar = "";
    /** If the current page is an article page */
    if (location.href.indexOf("articles") > 0) {
        /** The SideBar class object is created */
        sidebar = new SideBar();
        /** The SideBar class object is initialized */
        sidebar.Initialize();
    }
});
