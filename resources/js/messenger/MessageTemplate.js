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
        const date = new Date(message.date.date);
        const read = message.read;
        const element = document.importNode(this.template.content, true);
        const outputBox = element.querySelector('output');
        const dialogTemplate = $('#modal-dialog');
      //  const dialogEl = document.importNode(dialogTemplate.content,true);
        const modal = $('#myModalDialog');

     //   modal.find('.modal-title').text('Vous avez reçu un nouveau message !');
        modal.find('.modal-body').append(message.content);


        let dialogOpen = 0;
        element.querySelector(this.nameSelector).textContent = message.author;
        element.querySelector(this.dateSelector).textContent = MessageTemplate.renderTime(date);
        element.querySelector(this.contentSelector).innerHTML = "";
        if(!message.read) {

            //MessageTemplate.type(0, 50, message.content.toString(), element.querySelector(this.contentSelector));
            element.querySelector(this.contentSelector).innerHTML = message.content;
            modal.find('.modal-body').empty();
            modal.find('.modal-title').empty();
            modal.find('.modal-title').append("<span class=\"badge badge-danger\">Nouveau Message de  "+message.author+"</span>");
            modal.find('.modal-body').append(message.content);
            $('#myModalDialog').modal('show');

/*
            let myDialog = document.createElement("dialog");
            myDialog.setAttribute("style"," padding: 0;" +
                "  border: 0;" +
                "  border-radius: 0.6rem;" +
                "  box-shadow: 0 0 1em black;"
                );
            document.body.appendChild(myDialog);
            let pTitle = document.createElement("p");
            let pMessage = document.createElement("p");
            pTitle.setAttribute("style","text-align:center");
            pMessage.setAttribute("style","text-align:center");
            myDialog.appendChild(pTitle);
            myDialog.appendChild(pMessage);
            let text = document.createTextNode("Vous avez reçu un nouveau message !");
            let messageYes = document.createTextNode(message.content.toString());
            let cancelButton = document.createElement('button');
            cancelButton.setAttribute("style",
                "  right: 0.1em;" +
                "  background-color: transparent;" +
                "  border: 1;"
                );
            cancelButton.appendChild(document.createTextNode('Fermer'));
            pTitle.appendChild(text);
            pMessage.appendChild(messageYes);
            myDialog.appendChild(cancelButton);
            myDialog.showModal();
            cancelButton.addEventListener("click", ()=>{
                myDialog.close("closedMessage");
            });*/
        }
        else {

            element.querySelector(this.contentSelector).innerHTML = message.content;

        }
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