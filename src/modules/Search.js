
import $ from 'jquery';
class Search {
    //1/describe and create  our objext
    // this is like document.querySelector
    constructor (){
   this.openButton=$(".js-search-trigger")
   this.closeButton=$(".search-overlay__close")
   this.searchOverlay=$(".search-overlay")
   this.searchField=$("#search-item");
   this.events()
   this.isOVerlayOpen=false;
   this.typingTimer;

    }
    //2/ events
     // this is like addEventListener
 events(){
    this.openButton.on("click",this.openOverlay.bind(this)); 
    this.closeButton.on("click",this.closeOverlay.bind(this) );
    $(document).on("keydown",this.keyPressdispatcher.bind(this));
    this.searchField.on("keydown",this.typingLogic.bind(this))
 }

    // 3/methods (function or action)
    typingLogic(){
        clearTimeout(this.typingTimer)
 this.typingTimer=setTimeout(function(){
    console.log('this is a time ou6t');
},2000)
    }
    keyPressdispatcher(e){
        if(e.keyCode==83 && !this.isOVerlayOpen){
            this.openOverlay();
        }
        if(e.keyCode==27 && this.isOVerlayOpen){
            this.closeOverlay()
        }
    }
    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll")
        this.isOVerlayOpen=true;

    }
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active")
        $("body").removeClass("body-no-scroll")
        this.isOVerlayOpen=false;
    }
}
export default Search