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
            '<button type="submit" class="btn btn-primary">Déclencher le timer</button>');
        $(form).submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            $.ajax('gm/startChrono',{
                data: $(form).serialize(), // serializes the form's elements.
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


        });
        $(container).append(form);
        this.root.append(container);
    }
}

exports.ChronometrageForm = ChronometrageForm;
