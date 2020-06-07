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

    updateTeams(teamsJSON){
        const teams = teamsJSON.teams;
        this.root.append('<form>' +
            '<div class="form-group">' +
            '<label for="FormControlSelectVague">Choix de la vague</label>' +
            '<select class="form-control" id="FormControlSelectVague">' +
            '  <option>1</option>' +
            '  <option>2</option>' +
            '  <option>3</option>' +
            '  <option>4</option>' +
            '  <option>5</option>' +
            '  <option>6</option>' +
            '  <option>7</option>' +
            '  <option>8</option>' +
            '  <option>9</option>' +
            '  <option>10</option>' +
            '\t\t</select>\n' +
            '\t</div>\n' +
            '\t<button type="submit" class="btn btn-primary">Déclencher le timer</button>\n' +
            '</form>')

    }

    update(){
        $.ajax()
    }


}

exports.ChronometrageForm = ChronometrageForm;
