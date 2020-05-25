/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
//
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
//
// const app = new Vue({
//     el: '#app',
// });

const $ = require('jquery');

// jQuery-ui
require('jquery-ui/ui/widgets/sortable');
$(document).on("mousedown", function (e1) {
    $(document).one("mouseup", function (e2) {
        if (e1.which === 2 && e1.target === e2.target) {
            const e3 = $.event.fix(e2);
            e3.type = "middleclick";
            $(e2.target).trigger(e3);
            if (e3.isDefaultPrevented()) {
                e2.preventDefault();
            }

        }
    });
});

// jQuery template clone plugin
(function ($) {
    const default_clone = $.fn.clone;

    $.fn.clone = function () {
        if (this.is('template')) {
            return $(this[0].content).clone();
        } else {
            return default_clone.call(this);
        }
    };

    $.fn.exists = function () {
        return this.length > 0;
    }
}(jQuery));

// Custom
TabList = require('./tabs').TabList;
GMTeam = require('./gm/gm_team').GMTeam;
GMTeamList = require('./gm/gm_team').GMTeamList;
PlayerRiddle = require('./player/player_riddle').PlayerRiddle;
PlayerRiddleGrid = require('./player/player_riddle').PlayerRiddleGrid;
RoomList = require('./messenger/createRoom').RoomList;