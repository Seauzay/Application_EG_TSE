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

    createMessage(message) {
        const date = new Date(message.date.date);        
        const element = document.importNode(this.template.content, true);
        element.querySelector(this.nameSelector).textContent = message.author;
        element.querySelector(this.dateSelector).textContent = MessageTemplate.renderTime(date);
        element.querySelector(this.contentSelector).textContent = message.content;
        if(message.self)
            element.querySelector('.message').classList.add('self');
        return element;
    }

    static renderTime(date) {    	
        let min = date.getHours() * 60 + date.getMinutes();
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