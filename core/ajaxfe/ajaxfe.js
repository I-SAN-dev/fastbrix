/**
 * This file enables the frontend ajax loading functionality
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

(function(){

    /* define some variables */
    var content;
    var loading;
    var title;
    var linktoload;

    var animAttrs = {
        duration: 100
    };

    var animAttrsNoQueue =
    {
        duration: 100,
        queue: false
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function(){

        loading = $('#fastbrixLoading');
        content = $('#fastbrixContent');
        title = $('title');

        // Check if history API is available, do nothing if not (fallback to non-ajax page)
        if (history.pushState && history.replaceState) {
            initAJAX();
        }

        updateActiveClass();

    });


    /**
     * Inits the AJAX functionality
     */
    var initAJAX = function()
    {
        /* Initialize loading */
        var links = $('a.ajax');

        links.click(function(e){

            e.preventDefault();
            var that = $(this);
            var  link = that.attr('href');

            /* load new page */
            loadState(link, false);

            /* scroll to top if necessary */
            if (that.hasClass('totop')) {
                $('html, body').animate({
                    scrollTop: 100
                }, {
                    duration: 500,
                    queue: false
                });
            }
        });

        /*Initialize history back events */
        window.onpopstate = function(event)
        {
            console.log(event);

            if (event.state) {
                applyState(event.state);
                updateActiveClass(event.state.link)
            }
            else
            {
                loadState(window.location.protocol + "//" + window.location.host + "" + window.location.pathname, true);
            }
        }
    };

    /**
     * Loads page content
     * @param link (string) the link to load
     * @param oldstate (boolean) set to true if that state should not be pushed
     */
    var loadState = function(link, oldstate)
    {
        linktoload = link;
        content.height(content.height());
        content.addClass('fastbrixHidden');
        loading.fadeIn(animAttrsNoQueue);

        $.post(link)
            .done(function(data){

                applyState(data);
                data.link = link;

                // Update the URL / history
                if (!oldstate) {
                    history.pushState(data, data.title, link);
                }
                else
                {
                    history.replaceState(data, data.title, link);
                }

                // Update links
                updateActiveClass(linktoload);
            })
            .fail(function(data){
                loadState(linktoload, oldstate);
            });
    };

    /**
     * Applies the loaded state
     * @param data (object) a data object
     * @param link (string) the link to the page that is applied
     */
    var applyState = function(data)
    {
        // Set the HTML
        content.html(data.content);
        content.height('auto');
        loading.fadeOut(animAttrsNoQueue);
        content.removeClass('fastbrixHidden');

        // Set the title
        title.html(data.title);

        /* call the callback for other brixes scripts */
        window.onFastbrixAjaxPageChange();

    };

    /**
     * Sets active class on links to current page
     */
    var updateActiveClass = function(linktoload)
    {
        /* deactivate */
        $('.active').removeClass('active');

        /* activate */
        var linktohighlight = linktoload;
        if(!linktohighlight)
        {
            linktohighlight = location.pathname;
        }

        $("a[href='" + linktohighlight + "']").each(function(){

            var that = $(this);
            that.blur();
            var parentli = that.parent('li');
            if(parentli.length > 0)
            {
                /* we are inside a nav - highlight the li instead of the a */
                parentli.addClass('active');

                /* check for parent menu entries */
                var parentmenuli = parentli.parent().parent('li');
                if(parentmenuli.length > 0)
                {
                    /* this is a parent menu entry! */
                    parentmenuli.addClass('active');
                }
            }
            else
            {
                that.addClass('active');
            }

        });
    }


})();

