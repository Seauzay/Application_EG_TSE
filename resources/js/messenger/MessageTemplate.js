var moment = require('moment');
class MessageTemplate {
    constructor(param) {
        if (param instanceof MessageTemplate)
            return param;
        if (typeof param === 'string')
            param = document.querySelector(param);
        if (param instanceof HTMLElement) {
            param = {
                element: param,
            }
        }
        this.template = param.element;
        this.nameSelector = param.nameSelector || '.name';
        this.dateSelector = param.dateSelector || '.date';
        this.contentSelector = param.contentSelector || '.content';
    }
    static  type(indexOfBeg, time,message, output) {
        let input = message;
        output.innerHTML += input.charAt(indexOfBeg);
        setTimeout(function(){
            ((indexOfBeg < input.length - 1) ? MessageTemplate.type(indexOfBeg+1, time, message, output) : false);
        }, time);
    }
    createMessage(message) {
        const date = moment(message.date.date,"YYYY-MM-DD hh:mm:ss");
        const element = document.importNode(this.template.content, true);
        const modal = $('#myModalDialog');

        modal.find('.modal-body').append(message.content);


        element.querySelector(this.nameSelector).textContent = message.author;
        element.querySelector(this.dateSelector).textContent = MessageTemplate.renderTime(date);
        element.querySelector(this.contentSelector).innerHTML = "";
        if(!message.read) {

            element.querySelector(this.contentSelector).innerHTML = message.content;
            modal.find('.modal-body').empty();
            modal.find('.modal-title').empty();
            modal.find('.modal-title').append("<span class=\"badge badge-danger\">Nouveau Message de  "+message.author+"</span>");
            modal.find('.modal-body').append(message.content);
            $('#myModalDialog').modal('show');

        }
        else {

            element.querySelector(this.contentSelector).innerHTML = message.content;

        }
        if(message.self)
            element.querySelector('.message').classList.add('self');
        return element;
    }

    static renderTime(date) {
        let min = date.hours() * 60 + date.minutes();
        const hour = Math.floor(min / 60);
        min %= 60;
        return this.renderWithZero(hour) + ':' + this.renderWithZero(min);
    }

    static renderWithZero(number) {
        if(number < 10)
            return '0' + number;
        return '' + number;
    }

}

exports.MessageTemplate = MessageTemplate;
