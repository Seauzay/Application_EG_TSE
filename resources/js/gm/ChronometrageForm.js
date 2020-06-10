class ChronometrageForm {

    constructor(root) {
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of ChronometrageForm.';
            root = $(root);
        }
        this.root = root;
        this.id = root.prop('id');
    }

    fillHTML(){
        let container = document.createElement('div');
        container.setAttribute("id","ChronometrageContainer");
        container.setAttribute("class","jumbotron");
        let form = document.createElement('form');
        form.setAttribute("id","chronoForm");
        $(form).append('<div class="form-group">' +
            '<label for="FormControlSelectVague">Choix de la vague</label>' +
            '<select class="form-control" name="selectedVague" id="FormControlSelectVague">' +
            '  <option value ="1">1</option>' +
            '  <option value ="2">2</option>' +
            '  <option value ="3">3</option>' +
            '  <option value ="4">4</option>' +
            '  <option value ="5">5</option>' +
            '  <option value ="6">6</option>' +
            '  <option value ="7">7</option>' +
            '  <option value ="8">8</option>' +
            '  <option value ="9">9</option>' +
            '  <option value ="10">10</option>' +
            '</select>' +
            '</div>' +
            '<div class ="form-group">'+
            '<button type="button" id="triggerButton" class="btn btn-primary" name="action" value="trigger">Déclencher le timer</input>' +
            '&nbsp;'+
            '<button type="button" id="resetButton" class="btn btn-primary" name="action" value="reset">Remettre le timer à zéro</input>' +
            '</div>');
        let copyThis = this;
        console.log($("#triggerButton"));
        $(document).ready(function(){
            $("#triggerButton").on('click',function() {
                console.log('test');
                let formData = $('#chronoForm').serializeArray();
                formData.push({ name: this.name, value: this.value });
                copyThis.submitForm(formData);
            });
            $("#resetButton").on('click',function() {
                console.log('test');
                let formData = $('#chronoForm').serializeArray();
                formData.push({ name: this.name, value: this.value });
                copyThis.submitForm(formData);
            });
        });
        $(container).append(form);
        this.root.append(container);
    }

    submitForm(formData){
        $.ajax('gm/startChrono',{
            data: formData, // serializes the form's elements.
            success: function(data) // show response from the php script.
            {
                if (data.status.type === 'success') {
                    // show modal for success
                    alert(data.status.message);

                }
                if (data.status.type === 'error') {
                    // show modal for error
                    alert(data.status.message);
                }
            }
        });
    }
}

exports.ChronometrageForm = ChronometrageForm;
