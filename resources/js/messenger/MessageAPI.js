const MessageTemplate = require('./MessageTemplate').MessageTemplate;
const MessageForm = require('./MessageForm').MessageForm;

class MessageAPI {
    constructor(room, container, template, form,tabList) {
        this.container = (typeof container === 'string' ? document.querySelector(container) : container);
        this.room = room;
        this.form = new MessageForm(form);
        this.refreshTimeout = null;
        this.refreshDelay = 10000; // 10 seconds
        this.tabList = tabList;
        this.template = new MessageTemplate(template);
        this.form.handler = data => this.refreshMessages();
        this.last = new Date(0);

        this.refreshMessages();
    }

    refreshMessages() {
        $.ajax('msg/' + this.room, {
            method: 'get',
            async : false,
            dataType: 'json',
            error: (jqXHR, textStatus, errorThrown) => {
                console.error(textStatus || errorThrown);
                console.error(jqXHR);
            },
            success: (data, textStatus, jqXHR) => {
                //this.container.innerHTML = '';
                for (const message of data.messages) {

                    if(new Date(message.date.date) > this.last) {
                        this.last = new Date(message.date.date);
                        if(this.callback)
                            this.callback();
                        const element = this.template.createMessage(message);
                        this.container.appendChild(element);

                    }
                }
            }
        });

        $.ajax('msg/update/'+this.room, {
            success: (data) => {
            }
        });
    }
}

exports.MessageAPI = MessageAPI;
