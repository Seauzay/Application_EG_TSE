class CreateModParcourDisp{
    constructor(tabList) {
        this.tablist = tablist;
        this.parcourAPI = null;
        const pos = this.tablist.addTab({title: "Gestion des parcours"});
        //const api = createRoom(this.tablist.contentOfTab(pos + 1), id);
        this.createDisplay(this.tablist.contentOfTab(pos + 1))
    }

    createDisplay(where){
        //const pos = this.tablist.addTab({title: 'Modifier parcours'});

        where = $(where);
        if (!window.modParcourTemplate) {
            window.modParcourTemplate = new ModParcourTemplate('#mod-parcour-template');
        }
        const node = document.querySelector('#mod-parcour-display-template')
        where.append(node);
        this.parcourAPI = new ModParcourAPI(where.find('.mod-parcour-container')[0], window.modParcourTemplate);
    }


    allowDrop(ev) {
        this.parcourAPI.allowDrop(ev);
    }

    drag(ev) {
        this.parcourAPI.drag(ev);
    }

    drop(ev) {
        this.parcourAPI.drop(ev);
    }
    dragOver(ev){
        this.parcourAPI.dragOver(ev);
    }
    resetBDD(){
        this.parcourAPI.displayRiddles = [];
        this.parcourAPI.selectedIndex = -1;
        this.parcourAPI.AllRiddles=[];
        this.parcourAPI.getElementFromDB();
    }
    modBDD(){
        this.parcourAPI.modBDD();
    }

}

class ModParcourAPI {
    constructor(container, template) {
        this.container = (typeof container === 'string' ? document.querySelector(container) : container);
        this.template = new ModParcourTemplate(template);
        this.AllRiddles = [];
        this.parcours = [];
        this.displayRiddles = [];
        this.selectedIndex = -1;
        this.draggedElementId = -1; //element dragged
        this.dragOverShadow = null;
        this.getElementFromDB();
    }


    getElementFromDB() {
        // get all riddles
        $.ajax('riddleteam/fullList', {
            method: 'GET', success: (response) => {
                //this.container.innerHTML = ''
                if (this.AllRiddles != response.riddles) {
                    this.AllRiddles = response.riddles;
                }
            }
        });
        $.ajax('riddleteam/list', {
            method: 'get',
            dataType: 'json',
            error: (jqXHR, textStatus, errorThrown) => {
                console.error(textStatus || errorThrown);
                console.error(jqXHR);
            },
            success: (response) => {
                this.updateParcours(response)
            }
        });
    }
    getColorFromParcour(name){
        switch(name){
            case "Rouge" : clr="red";
                break;
            case "Vert" : clr="green";
                break;
            case "Bleu" : clr="blue";
                break;
            case "Jaune" : clr="yellow";
                break;
            case "Violet" : clr="violet";
                break;
            default :
                clr="gray";
        }
        return clr;
    }
    changeIdx(newIdx){
        this.selectedIndex = newIdx;
        var header_list = document.querySelector("#header-mod-parcours");
        header_list.style.borderColor = this.getColorFromParcour(this.parcours[newIdx]);
        this.update();
    }
    updateDisplay() {
        //Create Parcours array
        var header_parcours = document.querySelector("#header-mod-parcours");
        header_parcours.innerHTML = '';
        //var content_parcours = document.querySelector("#content-mod-parcours");
        for (let parc of this.parcours) {
            //set header
            var title = document.createElement('h2');
            var btn = document.createElement('div');
            btn.appendChild(title);
            title.textContent = parc;
            var clr = this.getColorFromParcour(parc);

            btn.style.backgroundColor = clr;
            let idx = this.parcours.indexOf(parc);
            btn.addEventListener("click",ev => this.changeIdx(idx));
            header_parcours.appendChild(btn);
            this.selectedIndex = 0;
            header_parcours.style.borderColor = this.getColorFromParcour(this.parcours[0]);
        }
    }

    updateParcours(parcourJSON) {
        //get only one team per color
        var parcour_color_name = ['Rouge', 'Vert', 'Bleu', 'Jaune', 'Violet'];
        this.colorParcour = [];
        this.parcours = [];
        for (let data of parcourJSON.data) {
            let idx = -1;
            for (let colorName of parcour_color_name)
                if (data.team.name.indexOf(colorName) != -1)
                    idx = parcour_color_name.indexOf(colorName);


            if (idx != -1) {
                // There's at least on
                let parcClr = parcour_color_name[idx];
                this.colorParcour.push(data);
                this.parcours.push(parcClr);
                parcour_color_name.splice(parcour_color_name.indexOf(parcClr), 1);
                var riddlesTab = [];
                this.displayRiddles.push(riddlesTab);
            }
        }

        // there is a diff between the riddles from the parcours and the riddles from this.AllRiddles => remove this diff
        for(let parcour of this.colorParcour){
            for(let rid of parcour.riddles){
                let riddleF = this.AllRiddles.filter(v => v.id == rid.id);
                if(riddleF != null){
                    this.colorParcour[this.colorParcour.indexOf(parcour)].riddles.splice(parcour.riddles.indexOf(rid),1,riddleF[0]);
                }
            }
        }
        this.updateDisplay();
        this.update();
    }

    update() {
        if (this.selectedIndex >= 0) {
            var dataParc = this.colorParcour[this.selectedIndex];
            var content_riddles = document.querySelector("#possible-riddle");
            var content_parcours = document.querySelector("#mod-parcours");
            var oldParcourContent = document.querySelectorAll(".mod-parcours-content");
            for (let d of oldParcourContent)
                content_parcours.removeChild(d);
            var oldRiddleContent = document.querySelectorAll(".mod-riddles-content");
            for (let d of oldRiddleContent)
                content_riddles.removeChild(d);
            var found = false;

            for (let rid of dataParc.riddles) {
                var riddle_content = document.createElement('li');
                var riddle = this.AllRiddles.filter(v => v.id == rid.id)[0];
                riddle_content.appendChild(this.template.createCard(riddle));
                riddle_content.className = 'mod-parcours-content';
                riddle_content.id='riddle-'+riddle.id;
                content_parcours.appendChild(riddle_content);
            }

            for(let rid of this.displayRiddles[this.selectedIndex]){
                var riddle_content = document.createElement('li');
                var riddle = this.AllRiddles.filter(v => v.id == rid.id)[0];
                riddle_content.appendChild(this.template.createCard(riddle));
                riddle_content.className = 'mod-riddles-content';
                riddle_content.id='riddle-'+riddle.id;
                content_riddles.appendChild(riddle_content);
            }

            for(let rid of this.AllRiddles){
                if(!(dataParc.riddles.some(r => r.id === rid.id)) && !(this.displayRiddles[this.selectedIndex].some(r => r.id === rid.id))){
                    this.displayRiddles[this.selectedIndex].push(rid);
                    var riddle_content = document.createElement('li');
                    riddle_content.appendChild(this.template.createCard(rid));
                    riddle_content.className = 'mod-riddles-content';
                    riddle_content.id='riddle-'+rid.id;
                    content_riddles.appendChild(riddle_content);
                }
            }
        }

    }

    allowDrop(ev) {
        ev.preventDefault();
    }

    drag(ev) {
        this.draggedElementId = ev.target.querySelector('.id-card').textContent;
        ev.dataTransfer.setData("text", ev.target.id);
    }


    switchArray(elementId, destId, destClass) {
        if (destClass === 'riddle-list' ||destClass === 'mod-riddles-content' || destClass === 'mod-parcours-content') {
            //check if element to delete not the same as the one to move :
            var destIdel = destId.replace("riddle-", "");
            if(elementId != destIdel) {
                //remove element from the source array
                var removeElement = this.AllRiddles.filter(v => v.id == elementId)[0];
                let idx = this.displayRiddles[this.selectedIndex].indexOf(removeElement);
                if (idx != -1) {
                    this.displayRiddles[this.selectedIndex].splice(idx, 1);
                } else {
                    idx = this.colorParcour[this.selectedIndex].riddles.indexOf(removeElement);
                    if (idx != -1)
                        this.colorParcour[this.selectedIndex].riddles.splice(idx, 1);
                }

                //put the element to the new array
                if (destId === 'mod-parcours') {
                    this.colorParcour[this.selectedIndex].riddles.push(removeElement);
                    let idx = this.displayRiddles[this.selectedIndex].indexOf(removeElement);
                } else if (destId === '_possible-riddle') {
                    this.displayRiddles[this.selectedIndex].push(removeElement);
                } else {
                    if (destClass === 'mod-riddles-content') {
                        var riddleId = destId.replace("riddle-", "");
                        var elementToPush = this.displayRiddles[this.selectedIndex].filter(v => v.id == riddleId)[0];
                        var idxToPush = this.displayRiddles[this.selectedIndex].indexOf(elementToPush);
                        if (idxToPush != -1) {
                            this.displayRiddles[this.selectedIndex].splice(idxToPush, 0, removeElement);
                        }
                    } else if (destClass === 'mod-parcours-content') {
                        var riddleId = destId.replace("riddle-", "");
                        var elementToPush = this.colorParcour[this.selectedIndex].riddles.filter(v => v.id == riddleId)[0];
                        var idxToPush = this.colorParcour[this.selectedIndex].riddles.indexOf(elementToPush);
                        if (idxToPush != -1) {
                            this.colorParcour[this.selectedIndex].riddles.splice(idxToPush, 0, removeElement);
                        }
                    }
                }
            }
        }
    }

    drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var target = ev.target;
        while (target.className.indexOf('mod-riddles-content') == -1 && target.className.indexOf('mod-parcours-content') == -1 && target.className.indexOf('riddle-list') == -1) {
            target = target.parentNode;
        }
        this.switchArray(this.draggedElementId, target.id, target.className);
        //update
        this.update();
        this.draggedElementId = -1;
    }

    dragOver(ev){
        var target = ev.target;
        while (target.className.indexOf('mod-riddles-content') == -1 && target.className.indexOf('mod-parcours-content') == -1 ){
            target = target.parentNode;
        }
        var containerSha = document.getElementById(target.id);
        if(this.dragOverShadow == null){
            this.dragOverShadow = document.createElement('div');
            this.dragOverShadow.id = 'drag-over-shadow';
        }
        if(containerSha != null && !containerSha.contains(this.dragOverShadow)){
            containerSha.prepend(this.dragOverShadow);
        }
    }
}

class ModParcourTemplate{

    constructor(param){
        if (param instanceof ModParcourTemplate)
            return param;
        if (typeof param === 'string')
            param = document.querySelector(param);
        if (param instanceof HTMLElement) {
            param = {
                element: param,
            }
        }
        this.container = param.element;
    }

    createCard(riddleJSON){
        if ("content" in document.createElement("template")) {
            // On prépare une ligne pour le tableau
            var clone = document.importNode(this.container.content, true);
            //var card = clone.querySelector('.card-admin');
            var cnt = clone.childNodes;
            var title = clone.querySelector('.current-riddle-name');
            var descr = clone.querySelector('.current-riddle-descr');
            var code = clone.querySelector('.current-riddle-code');
            var url = clone.querySelector('.current-riddle-url');
            var post_msg = clone.querySelector('.current-riddle-post-msg');
            var cb_activated = clone.querySelector('.current-riddle-activated');
            var id_card = clone.querySelector('.id-card');
            title.textContent=riddleJSON.name;
            descr.textContent=riddleJSON.description;
            code.textContent=riddleJSON.code;
            if(riddleJSON.post_resolution_message != null)
                post_msg.textContent=riddleJSON.post_resolution_message
            else
                post_msg.remove();
            url.href=riddleJSON.url;
            id_card.textContent=riddleJSON.id;
            return clone;
        } else {
            // Une autre méthode pour ajouter les lignes
            // car l'élément HTML n'est pas pris en charge.
        }
    }
}

exports.CreateModParcourDisp = CreateModParcourDisp;
exports.ModParcourAPI = ModParcourAPI;
exports.ModParcourTemplate = ModParcourTemplate;
