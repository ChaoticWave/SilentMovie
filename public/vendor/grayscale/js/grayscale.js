/*!
 * Start Bootstrap - Grayscale Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 * 
 * Modified by chaoticwaver@gmail.com
 */

/**
 * Scroll to element $target
 *
 * @param {string} elem
 * @param {object} [event]
 */
function scrollPage(elem, event) {
    var $elem = $(elem).offset();

    $('html, body').stop().animate({
            scrollTop: $elem && $elem.top - 70
        }, 1500, 'easeInOutExpo'
    );

    event && event.preventDefault();
}

/**
 * jQuery to collapse the navbar on scroll
 */
function collapseNavbar() {
    if ($('.navbar').offset().top > 50) {
        return $('.navbar-fixed-top').addClass('top-nav-collapse');
    }

    //noinspection JSJQueryEfficiency
    return $('.navbar-fixed-top').removeClass('top-nav-collapse');
}

$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);

/**
 * DocReady
 */
$(function() {
        $('a.page-scroll').bind('click', function(event) {
                scrollPage($(this).attr('href'), event);
            }
        );

        //noinspection JSUnresolvedVariable
        if (_prefilled) {
            scrollPage('#search-results');
        }
    }
);

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
        $(this).closest('.collapse').collapse('toggle');
    }
);
