/**
 * Delay JS Centered Nav addon.
 * Only loaded when the theme option "Centered Logo Menu" is enabled.
 *
 * @package Salient
 * @author ThemeNectar
 */

 (function($, window, document) {

  "use strict";

  function NectarDelayJSCenteredLogo() {
    this.windowWidth = window.innerWidth;
    this.windowHeight = window.innerHeight;
    this.init();
  }

  const proto = NectarDelayJSCenteredLogo.prototype;

  proto.isVisible = function(element) {
    return element.offsetWidth > 0 || element.offsetHeight > 0;
  }

  proto.init = function() {

    const self = this;

     if ( document.querySelector('#header-outer[data-format="centered-logo-between-menu"]') ) {
      const usingLogoImage = document.querySelector('#header-outer[data-using-logo="1"]');

      if (!usingLogoImage) {
          self.centeredLogoNav();
      } else if ( usingLogoImage && document.querySelector('header#top #logo img[src]')) {

          const tempLogoImg = new Image();
          tempLogoImg.src = document.querySelector('header#top #logo img').getAttribute('src');

          tempLogoImg.onload = function() {
              self.centeredLogoNav();
          };
      }
    }

  };

  proto.centeredLogoNav = function() {

    const colSpan9 = document.querySelector('#top .row > .col.span_9');
    const colSpan3 = document.querySelector('#top .row .col.span_3');
    const firstMenuItem = document.querySelector('#top nav > ul > li:first-child > a');

    if( !colSpan9 || !firstMenuItem || !colSpan3 ) {
        return;
    }

    if (this.windowWidth > 1000) {
        let navItemLength = document.querySelectorAll('#header-outer #top nav > .sf-menu:not(.buttons) > li').length;
        
        if (document.querySelector('#header-outer #social-in-menu')) {
            navItemLength--;
        }

        let centerLogoWidth, extraMenuSpace;
      
        const logoImages = colSpan3.querySelectorAll('img');
        let visibleImage = null;

        for (var i = 0; i < logoImages.length; i++) {
            if (this.isVisible(logoImages[i])) {
                visibleImage = logoImages[i];
                break;
            }
        }


        if (!visibleImage) {
            centerLogoWidth = parseInt(colSpan3.offsetWidth);
        } else {
            centerLogoWidth = parseInt(visibleImage.offsetWidth);
        }
        
        const firstMenuItemComputedStyle = window.getComputedStyle(firstMenuItem);
        if (document.querySelector('#header-outer[data-lhe="animated_underline"]')) {
            extraMenuSpace = parseInt(firstMenuItemComputedStyle.getPropertyValue('margin-right'));
        } else {
            extraMenuSpace = parseInt(firstMenuItemComputedStyle.getPropertyValue('padding-right'));
        }
        
    
        if (extraMenuSpace > 30) {
            extraMenuSpace += 45;
        } else if (extraMenuSpace > 20) {
            extraMenuSpace += 40;
        } else {
            extraMenuSpace += 30;
        }
    
        const menuItems = document.querySelectorAll('#top nav > .sf-menu:not(.buttons) > li:nth-child(' + Math.floor(navItemLength / 2) + ')');
      
        for (var i = 0; i < menuItems.length; i++) {
            if (!document.body.classList.contains('rtl')) {
                menuItems[i].style.marginRight = (centerLogoWidth + extraMenuSpace) + 'px';
            } else {
                menuItems[i].style.marginLeft = (centerLogoWidth + extraMenuSpace) + 'px';
            }
            menuItems[i].classList.add('menu-item-with-margin');
        }
    
        let leftMenuWidth = 0;
        let rightMenuWidth = 0;
    
        const menuItemsAll = document.querySelectorAll('#top nav > .sf-menu:not(.buttons) > li:not(#social-in-menu)');
    
        for (var j = 0; j < menuItemsAll.length; j++) {
            if (j + 1 <= Math.floor(navItemLength / 2)) {
            leftMenuWidth += menuItemsAll[j].offsetWidth;
            } else {
            rightMenuWidth += menuItemsAll[j].offsetWidth;
            }
        }
    
        const menuDiff = Math.abs(rightMenuWidth - leftMenuWidth);
        
        if ( colSpan9 ) {
            if (leftMenuWidth > rightMenuWidth || (document.body.classList.contains('rtl') && leftMenuWidth < rightMenuWidth)) {
                colSpan9.style.paddingRight = menuDiff + 'px';
            } else {
                colSpan9.style.paddingLeft = menuDiff + 'px';
            }
        }
        
        const navs = document.querySelectorAll('#header-outer nav');
        navs.forEach((nav) => {
            nav.style.visibility = 'visible';
        });

      } else if (this.windowWidth < 1000) {
          colSpan9.style.paddingRight = '0';
          colSpan9.style.paddingLeft = '0';
      }
  }
  
  new NectarDelayJSCenteredLogo();

}(window.jQuery, window, document));