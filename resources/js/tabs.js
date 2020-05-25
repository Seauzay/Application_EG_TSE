/*!
Bootstrap Tabs v0.2

(c) Copyright 2019 Pierre Giraud

Licensed under the MIT license:
    http://www.opensource.org/licenses/mit-license.php

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

const lang_en = {
    default_title: 'New tab'
};

const lang_fr = {
    default_title: 'Nouvel onglet'
};

let tab_lang;
switch (document.documentElement.lang) {
    case 'fr':
        tab_lang = lang_fr;
        break;
    case 'en':
    default:
        tab_lang = lang_en;
}

function str2id(str) {
    return str
        .normalize('NFD').replace(/[\u0300-\u036f\W]/g, "") // remove accentuation and non-word character
        .toLowerCase();
}

class Tab {
    constructor(node, name, text) {
        this.node = node;
        node.click(() => {
            notify(false);
        })
    }

    notify(b = true) {
        if (b) {
            node.addClass('notifying');
        } else {
            node.removeClass('notifying');
        }
    }
}

class TabList {
    /**
     * Initializes a TabList on a given node.
     * @param root Root node of the list,as jQuery object or id string (e.g. '#taglist')
     */
    constructor(root) {
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }
        root.addClass('tabs');
        const navlist = root.find('.nav-tabs');
        if (navlist.length === 0) {
            root.append($('<ul>', {class: 'nav nav-tabs shadow-sm'})
                .attr('role', 'tablist')
                .sortable({
                    connectWith: root.find('.nav-tabs'),
                    axis: 'x'
                }));
            root.append($('<div>', {class: 'tab-content'}))
        } else {
            navlist.sortable({
                connectWith: root.find('.nav-tabs')
            });
        }

        this.root = root;
    }

    /**
     * Add a tab with several possible options.
     * <ul>
     *     <li>title : string (default : default_title) => the name shown on the tab</li>
     *     <li>closeable : boolean (default : false) => if the tab can be closed</li>
     *     <li>active : boolean (default : false) => if the tab is currently active</li>
     *     <li>id : string (default : 'nav-{title}-tab) => the id of the tab</li>
     *     <li>position : 'begin', 'end' or a number (default : 'end') => the position of the tab</li>
     * </ul>
     * @param options Struct of the options of the new tab.
     */
    addTab(options) {
        const settings = $.extend({
            title: tab_lang.default_title,
            closeable: false,
            active: false,
            position: 'end'
        }, options);

        if (settings.position === 'end')
            settings.position = this.root.find('ul').children().length;
        else if (settings.position === 'begin')
            settings.position = 0;
        else
            settings.position = Math.min(settings.position, this.root.find('ul').children().length);

        if (!settings.id) {
            settings.id = 'nav-' + str2id(settings.title) + '-tab';
        } else {
            settings.id = 'nav-' + str2id(settings.id) + '-tab';
        }

        if (this.root.find('#' + settings.id).length === 0) {
            let newTab = $('<li>', {class: 'nav-item'})
                .addClass(settings.closeable ? 'closeable' : '');
            let newTabLink = $('<a>', {class: 'nav-link', id: settings.id, href: '#' + settings.id.match('(nav-.*)-tab')[1]})
                .attr('role', 'tab')
                .attr('data-toggle', 'tab')
                .attr('aria-controls', settings.id.match('(nav-.*)-tab')[1])
                .text(settings.title);

            if (settings.closeable) {
                let cross = $('<span>', {class: 'ui-icon ui-icon-closethick ml-2'});
                cross.click(function (e) {
                    let tab = $(this).parent();
                    if (tab.hasClass('active'))
                        $('#tablist').find('ul').children().first().children().first().tab('show');
                    $('#tablist').find('#' + tab.prop('id').match('(nav-.*)-tab')[1]).remove();
                    tab.remove();
                });
                newTabLink.append(cross);
                newTabLink.addClass('pr-2');
            }

            newTab.append(newTabLink);
            newTab.click(function () {
                $(this).find('a').removeClass('notifying');
            });
            newTab.on('middleclick', function (e) {
                    if ($(this).hasClass('closeable')) {
                        if ($(this).children().first().hasClass('active'))
                            $('#tablist').find('ul').children().first().children().first().tab('show');
                        $('#tablist').find('#' + $(this).children().first().prop('id').match('(nav-.*)-tab')[1]).remove();
                        this.remove();
                    } else {
                        e.preventDefault();
                    }
                }
            );

            const newTabContent = $('<div>', {class: 'tab-pane', id: settings.id.match('(nav-.*)-tab')[1]})
                .attr('role', 'tabpanel')
                .attr('aria-labelledby', settings.id);

            if (settings.position === 0)
                this.root.find('ul').prepend(newTab);
            else
                this.root.find('li:nth-child(' + settings.position + ')').after(newTab);
            this.root.find('.tab-content').append(newTabContent);

            if (settings.active) {
                newTabLink.tab('show');
            }

            return settings.position;
        }
    }

    /**
     * Returns the nth tab of this TabList.
     * @param n the position of the tab
     * @returns {*|jQuery|Array} nth tab of this TabList
     */
    tab(n) {
        return this.root.find('li:nth-child(' + n + ') a');
    }

    /**
     * Shows the nth tab of this TabList. Same as left-clicking on it.
     * @param n the position of the tab
     */
    showTab(n) {
        this.tab(n).tab('show');
    }

    /**
     * Returns the content root node of the nth tab of this TabList.
     * @param n the position of the tab
     * @returns {*|jQuery|Array} the content root node of the nth tab of this TabList
     */
    contentOfTab(n) {
        const name = this.tab(n).prop('id').match('(nav-.*)-tab')[1];
        return $('#' + name);
    }

    /**
     * Make the nth tab of this TabList flicker or not.
     * @param n the position of the tab
     * @param b true if the tab must flicker, false elsewhen
     */
    notify(n, b = true) {
        if (b) {
            this.tab(n).addClass('notifying');
        } else {
            this.tab(n).removeClass('notifying');
        }
    }

    /**
     * Makes all the tabs flicker or not.
     * @param b true if the tabs must flicker, false elsewhen
     */
    notifyAll(b = false) {
        if (b) {
            this.root.find('ul li a').addClass('notifying');
        } else {
            this.root.find('ul li a').removeClass('notifying');
        }
    }
}

exports.TabList = TabList;