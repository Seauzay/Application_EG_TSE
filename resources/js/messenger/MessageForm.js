const $ = require('jquery');

class MessageForm {
    constructor(param) {
        if (param instanceof MessageForm)
            return param;
        if (typeof param === 'string')
            param = document.querySelector(param);
        if (param instanceof HTMLElement) {
            param = {
                element: param,
            }
        }
        this.form = param.element;

        this.setup();
    }

    setup() {
        this.form.addEventListener('submit',
            // _.debounce(
                e => {
                    e.preventDefault();
                    if ($(this.form).find('input').val() !== "") {
                        $.ajax(this.form.action, {
                            method: this.form.method || 'post',
                            dataType: 'json',
                            data: $(this.form).serialize(),
                            error: (jqXHR, textStatus, errorThrown) => {
                                console.error(textStatus || errorThrown);
                                console.error(jqXHR);
                            },
                            success: (data, textStatus, jqXHR) => {
                                if (this.handler)
                                    this.handler(data);
                                $(this.form).find('input').val('');
                            }
                        })
                    }
                })
            // ,
            // 250,
            // {
            //     leading: true,
            //     trailing: false
            // })
    }
}


exports.MessageForm = MessageForm;