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
    resetParcours(){
        var reponse = window.confirm("Reset les parcours et perdre les modifications non sauvegardées ?");
        if(reponse) {
            this.parcourAPI.resetParcours();
        }
    }


    modParcours(){
        var reponse = window.confirm("Enregistrer les modifications dans le BDD( pas de retour possible après confirmation) ?");
        if(reponse) {
            this.parcourAPI.modParcours();
        }
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

        // get all parcours
        $.ajax('riddleteam/getAllParcours', {
            method: 'get',
            dataType: 'json',
            error: (jqXHR, textStatus, errorThrown) => {
                console.error(textStatus || errorThrown);
                console.error(jqXHR);
            },
            success: (response) => {
               this.updateParcours(response);
            }
        });

    }

    modParcours(){
        if(this.parcours != [] && this.parcours.riddles_id != [] && this.parcours.length > 0){
            //save parcours
            var newParcours = [];
            for(var parc of this.parcours){
                newParcours.push({team_color : parc.team_color, riddles_id : parc.riddles_id});
            }

            //save riddles lines :
            var idLine = [];
            for(var riddle of this.AllRiddles){
                idLine.push({id : riddle.id, line : riddle.line});
            }

            $.ajax('riddleteam/modParcours', {
                data : {parcours : newParcours,
                    riddleLine : idLine},
                dataType: 'json'
                , success: (response) => {
                    alert(response.status.message);
                    this.resetParcours();
                }
            });

        }

    }

    getColorFromParcour(name){
        switch(name){
            case "Rouge" : clr="red";
                break;
            case "Vert" : clr="green";
                break;
            case "Bleu" : clr="blue";
                break;
            case "Jaune" : clr="#F9C11C";
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
        header_list.style.borderColor = this.getColorFromParcour(this.parcours[newIdx].team_color);
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
            title.textContent = parc.team_color;
            var clr = this.getColorFromParcour(parc.team_color);

            btn.style.backgroundColor = clr;
            let idx = this.parcours.indexOf(parc);
            btn.addEventListener("click",ev => this.changeIdx(idx));
            header_parcours.appendChild(btn);
            this.selectedIndex = 0;
            header_parcours.style.borderColor = this.getColorFromParcour(this.parcours[0].team_color);
        }
    }

    updateParcours(parcourJSON) {
        this.parcours = parcourJSON.parcours;
        for(let i = 0; i < this.parcours.length;i++)
            this.displayRiddles[i] = [];
        this.updateDisplay();
        this.update();
    }


    createLineDisplay(level,id){
        //creation de la ligne
        var riddle_line = document.createElement('li');
        riddle_line.className="mod-line-li";
        riddle_line.id=id;

        //création du header
        var lvlNbr = document.createElement('div');
        lvlNbr.className="lvl-nbr-header";
        lvlNbr.textContent=level;

        //création du content-div (qui va avoir les riddles)
        var riddle_content = document.createElement('div');
        riddle_content.className = "mod-line-content";
        //création du bouton de création de riddle :
        var addRiddle = document.createElement('div');
        addRiddle.className = "btn-add-riddle";
        var titleH2 = document.createElement('h2');
        titleH2.textContent="+";
        addRiddle.appendChild(titleH2);
        //riddle_content.appendChild(addRiddle);
        riddle_line.appendChild(lvlNbr);
        riddle_line.appendChild(riddle_content);

        riddle_line.addEventListener("dragover",ev=>this.dragOver(ev));
        return riddle_line;
    }

    createLinesAndCards(array_content_id,maxLvl){
        if(array_content_id == "possible-riddle" || array_content_id == "mod-parcours"){
            var contentList = document.querySelector("#"+array_content_id);
            var oldList = contentList.querySelectorAll(".mod-line-li");

            //remove all old elements
            for (let d of oldList)
                contentList.removeChild(d);

            var arrayToUpdateId = [];
            var line_id_prefix = "";

            if(array_content_id == "mod-parcours"){
                arrayToUpdateId = this.parcours[this.selectedIndex].riddles_id;
                line_id_prefix = "mod-parcours-line-";
            }else if(array_content_id == "possible-riddle"){
                arrayToUpdateId =  this.displayRiddles[this.selectedIndex];
                line_id_prefix = "mod-riddles-line-";
            }

            //on ajoute une ligne de plus !
            for(let i = 1; i <= maxLvl+1;i++){
                var riddle_line = this.createLineDisplay(i,line_id_prefix+i);
                contentList.appendChild(riddle_line);
            }

            //Update de la liste
            for (var rid_id of arrayToUpdateId) {
                //on récupère les informations de la riddle
                var riddle = this.AllRiddles.filter(v => v.id == rid_id)[0];
                // Création de la riddle :
                var riddleCard = this.template.createCard(riddle);
                this.template.collapseContent(riddleCard);
                //on l'ajoute au niveau voulue :
                var riddle_line = contentList.querySelector("#"+line_id_prefix+riddle.line).querySelector(".mod-line-content");
                riddle_line.prepend(riddleCard);
            }
        }
    }


    update() {
        if (this.selectedIndex >= 0) {
            for(var rid of this.AllRiddles){
                var dataParc = this.parcours[this.selectedIndex];
                if(!(dataParc.riddles_id.some(r_id => r_id === rid.id)) && !(this.displayRiddles[this.selectedIndex].some(r_id => r_id === rid.id))){
                    this.displayRiddles[this.selectedIndex].push(rid.id);
                }
            }

            //création des lines :
            //nombre de lines à créer :
            const arrayLines = this.AllRiddles.map(el => el.line);
            var maxLvl = Math.max(...arrayLines);

            this.createLinesAndCards("mod-parcours",maxLvl);
            this.createLinesAndCards("possible-riddle",maxLvl);
        }

    }

    allowDrop(ev) {
        ev.preventDefault();
    }

    drag(ev) {
        this.draggedElementId = ev.target.querySelector('.id-card').textContent;
        ev.dataTransfer.setData("text", ev.target.id);
    }



    resetParcours(){
        this.AllRiddles = [];
        this.parcours = [];
        this.displayRiddles = [];
        this.selectedIndex = -1;
        this.draggedElementId = -1; //element dragged
        this.dragOverShadow = null;
        this.getElementFromDB();
    }

    switchArray(elementId, destId, destClass) {
        if (destClass === 'mod-line-li') {
            //check if element to delete not the same as the one to move :
            var destIdel = destId.replace("riddle-", "");
            if(elementId != destIdel) {
                //remove element from the source array
                var removeElement = this.AllRiddles.filter(v => v.id == elementId)[0];
                var idx = this.displayRiddles[this.selectedIndex].indexOf(removeElement.id);
                if (idx != -1) {
                    this.displayRiddles[this.selectedIndex].splice(idx, 1);
                } else {
                    idx = this.parcours[this.selectedIndex].riddles_id.indexOf(removeElement.id);
                    if (idx != -1)
                        this.parcours[this.selectedIndex].riddles_id.splice(idx, 1);
                }

                //put the element to the new array
                if(destId.indexOf('mod-parcours-line-') != -1){
                    //la liste de destination est la liste correspondant aux parcours
                    this.parcours[this.selectedIndex].riddles_id.push(removeElement.id);

                    //on modifie le niveau de l'énigme (sera répercuté sur tout les parcours)
                    var newElement = removeElement;

                    var parsedInt = parseInt(destId.replace('mod-parcours-line-',''), 10);
                    if (isNaN(parsedInt))
                        parsedInt = 0;
                    newElement.line = parsedInt;
                    this.AllRiddles[this.AllRiddles.indexOf(removeElement)] = newElement;
                }else{
                    //liste correspond à la liste contenant les enigmes libres ou non trouvé
                    this.displayRiddles[this.selectedIndex].push(removeElement.id);
                }
            }
        }
    }

    drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var target = ev.target;
        while (target.className.indexOf('mod-line-li') == -1) {
            target = target.parentNode;
        }
        this.switchArray(this.draggedElementId, target.id, target.className);
        //update
        this.update();
        this.draggedElementId = -1;
    }

    dragOver(ev){
        var target = ev.target;
        while (target.className.indexOf('mod-line-li') == -1){
            target = target.parentNode;
        }
        var tgtId = target.id;
        if(tgtId.indexOf('mod-riddles-line-')!=-1){
            tgtId = 'mod-riddles-line-'+this.AllRiddles.filter(v => v.id == this.draggedElementId)[0].line;
        }
        var containerSha = document.getElementById(tgtId).getElementsByClassName('mod-line-content')[0];
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
            var collapse_div = clone.querySelector('.collapse-content');
            title.textContent=riddleJSON.name;
            descr.textContent=riddleJSON.description;
            code.textContent=riddleJSON.code;
            if(riddleJSON.post_resolution_message != null)
                post_msg.textContent=riddleJSON.post_resolution_message
            else
                post_msg.remove();
            url.href=riddleJSON.url;
            id_card.textContent=riddleJSON.id;
            let card = clone.querySelector('.card-admin');
            collapse_div.addEventListener("click",ev => {
                this.collapseContent(card);
            });
            return clone;
        } else {
            // Une autre méthode pour ajouter les lignes
            // car l'élément HTML n'est pas pris en charge.
        }
    }

    collapseContent(riddle){
        if(riddle != null){
            var cardContent = riddle.querySelector('.current-riddle-info');
            if(cardContent != null){
                if(cardContent.style.display == "none"){
                    cardContent.style.display = "";
                }else{
                    cardContent.style.display = "none";
                }
            }
        }

    }
}

exports.CreateModParcourDisp = CreateModParcourDisp;
exports.ModParcourAPI = ModParcourAPI;
exports.ModParcourTemplate = ModParcourTemplate;
