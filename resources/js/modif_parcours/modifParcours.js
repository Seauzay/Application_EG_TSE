class CreateModParcourDisp{
    constructor(tabList,isModParcours) {
        this.tablist = tablist;
        this.parcourAPIGestion = null;
        this.parcourAPIModRiddle = null;

        this.containerArray = [];
        const pos = this.tablist.addTab({title: "Gestion des parcours"});
        this.containerGestion = this.tablist.contentOfTab(pos + 1);
        if(isModParcours){
            const pos2 = this.tablist.addTab({title: "Modifier les énigmes"});
            this.containerModRiddle = this.tablist.contentOfTab(pos2+1);
        }
        this.isModParcours = isModParcours;
        this.createDisplay(isModParcours);

    }

    createDisplay(isModParcours){
        if (!window.modParcourTemplate) {
            window.modParcourTemplate = new ModParcourTemplate('#mod-parcour-template');
        }

        this.containerGestion = $(this.containerGestion);
        this.containerGestion.id = "#mod-parcour-display-template-gestion";
        const node = document.querySelector('#mod-parcour-display-template')
        node.id =this.containerGestion.id;
        this.containerGestion.append(node);
        this.parcourAPIGestion = new ModParcourAPI(this.containerGestion.find('.mod-parcour-container')[0], window.modParcourTemplate,true);
        this.containerArray.push(this.containerGestion);
        if(isModParcours){
            this.containerModRiddle = $(this.containerModRiddle);
            this.containerModRiddle.id = "#mod-parcour-display-template-riddle";
            const nodeMode = node.cloneNode(true);
            nodeMode.id = this.containerModRiddle.id;
            this.containerModRiddle.append(nodeMode);
            this.parcourAPIModRiddle = new ModParcourAPI(this.containerModRiddle.find('.mod-parcour-container')[0], window.modParcourTemplate,false);
            this.containerArray.push(this.containerModRiddle);
        }

    }


    allowDrop(ev) {
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        if(target.id == this.containerGestion.id)
            this.parcourAPIGestion.allowDrop(ev);
        else if(this.isModParcours && target.id == this.containerModRiddle.id)
            this.parcourAPIModRiddle.allowDrop(ev);
    }

    drag(ev) {
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        if(target.id == this.containerGestion.id)
            this.parcourAPIGestion.drag(ev);
        else if(this.isModParcours && target.id == this.containerModRiddle.id)
            this.parcourAPIModRiddle.drag(ev);

    }

    drop(ev) {
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        if(target.id == this.containerGestion.id)
            this.parcourAPIGestion.drop(ev);
        else if(this.isModParcours && target.id == this.containerModRiddle.id)
            this.parcourAPIModRiddle.drop(ev);
    }

    dragOver(ev){
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        if(target.id == this.containerGestion.id)
            this.parcourAPIGestion.dragOver(ev);
        else if(this.isModParcours && target.id == this.containerModRiddle.id)
            this.parcourAPIModRiddle.dragOver(ev);
    }
    resetParcours(ev){
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        var parcoursApi = null;
        var text = "";

        if(target.id == this.containerGestion.id){
            parcoursApi = this.parcourAPIGestion;
            text= "Reset les parcours et perdre les modifications non sauvegardées ?";
        }else if(this.isModParcours && target.id == this.containerModRiddle.id){
            parcoursApi = this.parcourAPIModRiddle;
            text = "Reset les énigmes et perdre les modifications non sauvegardées ?";
        }
        if(parcoursApi != null){
            var reponse = window.confirm(text);
            if(reponse)
                parcoursApi.resetParcours();
        }
    }


    modParcours(ev){
        var target = ev.target;
        while(this.containerArray.filter(ctnr => ctnr.id == target.id).length == 0)
            target = target.parentNode;

        var parcoursApi = null;
        var text = "Enregistrer les modifications dans le BDD( pas de retour possible après confirmation) ?";

        if(target.id == this.containerGestion.id)
            parcoursApi = this.parcourAPIGestion;
        else if(this.isModParcours &&  target.id == this.containerModRiddle.id)
            parcoursApi = this.parcourAPIModRiddle;

        if(parcoursApi != null){
            var reponse = window.confirm(text);
            if(reponse)
                parcoursApi.modParcours();
        }
    }

}

class ModParcourAPI {
    constructor(container, template,isModParcours) {
        this.container = (typeof container === 'string' ? document.querySelector(container) : container);
        this.template = new ModParcourTemplate(template);
        this.AllRiddles = [];
        this.parcours = [];
        this.displayRiddles = [];
        this.selectedIndex = -1;
        this.draggedElementId = -1; //element dragged
        this.dragOverShadow = null;
        this.isModParcours = isModParcours;
        this.getElementFromDB();
    }


    getElementFromDB() {
        // get all riddles
        $.ajax('riddleteam/fullList', {
            method: 'GET', success: (response) => {
                //this.container.innerHTML = ''
                if (this.AllRiddles != response.riddles) {
                    this.AllRiddles = response.riddles;
                    if(!this.isModParcours)
                        this.updateParcours();
                }
            }
        });

        if (this.isModParcours) {
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

    }

    modParcours(){
        //modifier les parcours
        if(this.isModParcours){
            if(this.parcours != [] && this.parcours.riddles_id != [] && this.parcours.length > 0) {
                //save parcours
                var newParcours = [];
                for (var parc of this.parcours) {
                    newParcours.push({team_color: parc.team_color, riddles_id: parc.riddles_id});
                }
                $.ajax('riddleteam/modParcours', {
                    data : {parcours : newParcours},
                    dataType: 'json'
                    , success: (response) => {
                        $('#success-modal').find('.modal-message').text(response.status.message);
                        $('#success-modal').modal('show');
                        this.resetParcours();
                    }
                });
            }
        }else{
            //save riddles lines :
            var idLine = [];
            for(var riddle of this.AllRiddles){
                idLine.push({id : riddle.id, line : riddle.line});
            }
            $.ajax('riddleteam/modRiddlesLvl', {
                data : {riddleLine : idLine},
                dataType: 'json'
                , success: (response) => {
                    $('#success-modal').find('.modal-message').text(response.status.message);
                    $('#success-modal').modal('show');
                    this.resetParcours();
                }
            });

        }
    }

    getColorFromParcour(name){
        switch(name){
            case "Rouge" : clr="#ea0000";
                break;
            case "Vert" : clr="#00b050";
                break;
            case "Bleu" : clr="#0070c0";
                break;
            case "Jaune" : clr="#F9C11C";
                break;
            case "Violet" : clr="#7030a0";
                break;
            default :
                clr="gray";
        }
        return clr;
    }
    changeIdx(newIdx){
        this.selectedIndex = newIdx;
        var header_list = this.container.querySelector("#header-mod-parcours");
        header_list.style.borderColor = this.getColorFromParcour(this.parcours[newIdx].team_color);
        header_list.style.backgroundColor = header_list.style.borderColor;
        this.update();
    }
    updateDisplay() {
        //Create Parcours array
        var header_parcours = this.container.querySelector("#header-mod-parcours");
        header_parcours.innerHTML = '';
        //var content_parcours = document.querySelector("#content-mod-parcours");
        for (let parc of this.parcours) {
            //set header
            var title = document.createElement('h4');
            title.style.marginTop = '8px';
            var btn = document.createElement('div');
            btn.style.cursor = 'pointer';
            btn.style.display = 'inline-block';
            btn.style.paddingRight = '5px';
            btn.style.paddingLeft = '5px';
            btn.style.marginRight = '2px';
            btn.style.marginLeft = '2px';
            btn.style.borderRadius = '2px';
            btn.style.border = '1px';
            btn.appendChild(title);
            title.textContent = parc.team_color;
            var clr = this.getColorFromParcour(parc.team_color);
            btn.style.backgroundColor = clr;
            btn.style.borderColor = clr;
            let idx = this.parcours.indexOf(parc);
            btn.addEventListener("click",ev => this.changeIdx(idx));
            header_parcours.appendChild(btn);
            this.selectedIndex = 0;
            header_parcours.style.borderColor = this.getColorFromParcour(this.parcours[0].team_color);
        }
        this.changeIdx(this.selectedIndex);
    }

    updateParcours(parcourJSON) {
        if(this.isModParcours) {
            this.parcours = parcourJSON.parcours;
            for (let i = 0; i < this.parcours.length; i++)
                this.displayRiddles[i] = [];
            this.updateDisplay();
        }else{
            this.container.querySelector("#mod-parcours").style.display = "none";
        }
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

        if(!this.isModParcours){
            //création du bouton de création de riddle :
            var addRiddle = document.createElement('div');
            addRiddle.addEventListener('click',ev => this.AddButtonClicked(level));
            addRiddle.className = "btn-add-riddle";
            var titleH2 = document.createElement('h2');
            titleH2.textContent="+";
            addRiddle.appendChild(titleH2);
            riddle_content.appendChild(addRiddle);
        }

        riddle_line.appendChild(lvlNbr);
        riddle_line.appendChild(riddle_content);

        riddle_line.addEventListener("dragover",ev=>this.dragOver(ev));
        return riddle_line;
    }

    createLinesAndCards(array_content_id,maxLvl){
        if(array_content_id == "possible-riddle" || array_content_id == "mod-parcours"){
            var contentList = this.container.querySelector("#"+array_content_id);
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
            if(!this.isModParcours)
                maxLvl++;
            for(let i = 1; i <= maxLvl;i++){
                var riddle_line = this.createLineDisplay(i,line_id_prefix+i);
                contentList.appendChild(riddle_line);
            }

            //Update de la liste
            for (let rid_id of arrayToUpdateId) {
                //on récupère les informations de la riddle
                var riddle = this.AllRiddles.filter(v => v.id == rid_id)[0];
                // Création de la riddle :
                var riddleCard = this.template.createCard(riddle);
                this.template.collapseContent(riddleCard);

                //on ajoute un bouton pour modifier l'énigme si on est en mode modification enigme
                if(!this.isModParcours){
                    var btn = document.createElement("button");
                    btn.className = "btn btn-primary validate-button my-1 center-block";
                    btn.addEventListener("click", ev=>this.ModButtonClicked(rid_id));
                    btn.textContent = "Modifier";
                    riddleCard.querySelector('.card-admin').appendChild(btn);
                }
                //on l'ajoute au niveau voulue :
                var riddle_line = contentList.querySelector("#"+line_id_prefix+riddle.line).querySelector(".mod-line-content");
                riddle_line.prepend(riddleCard);
            }
        }
    }

    ModButtonClicked(id){
        var riddle = this.AllRiddles.filter(r => r.id == id)[0];
        this.createCardModification(id,true,riddle.line);
    }

    AddButtonClicked(lvl){
        this.createCardModification(0,false,lvl);
    }

    update() {
        const arrayLines = this.AllRiddles.map(el => el.line);
        var maxLvl = Math.max(...arrayLines);
        if (this.selectedIndex >= 0 && this.isModParcours) {
            for(var rid of this.AllRiddles){
                var dataParc = this.parcours[this.selectedIndex];
                if(!(dataParc.riddles_id.some(r_id => r_id === rid.id)) && !(this.displayRiddles[this.selectedIndex].some(r_id => r_id === rid.id))){
                    this.displayRiddles[this.selectedIndex].push(rid.id);
                }
            }

            //création des lines :
            //nombre de lines à créer :

            this.createLinesAndCards("mod-parcours",maxLvl);
            this.createLinesAndCards("possible-riddle",maxLvl);
        }else if(!this.isModParcours){
            this.selectedIndex = 0;
            this.displayRiddles[this.selectedIndex] = this.AllRiddles.map(el =>el.id);
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
        this.container.style.opacity = 1;
        this.cancelModAdd();
        this.getElementFromDB();
    }

    switchArray(elementId, destId, destClass) {
        if (destClass === 'mod-line-li') {

            //check if element to delete not the same as the one to move :
            var destIdel = destId.replace("riddle-", "");
            if(this.isModParcours){
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
                        this.parcours[this.selectedIndex].riddles_id.push(removeElement.id)
                    }else{
                        //liste correspond à la liste contenant les enigmes libres ou non trouvé
                        this.displayRiddles[this.selectedIndex].push(removeElement.id);
                    }
                }
            }else{
                var parsedInt = parseInt(destId.replace('mod-riddles-line-',''), 10);
                if (isNaN(parsedInt))
                    parsedInt = -1;
                var elementToModify = this.AllRiddles.filter(v => v.id == elementId)[0];
                this.AllRiddles[this.AllRiddles.indexOf(elementToModify)].line = parsedInt;
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
        if(this.isModParcours){
            if(tgtId.indexOf('mod-riddles-line-')!=-1){
                tgtId = 'mod-riddles-line-'+this.AllRiddles.filter(v => v.id == this.draggedElementId)[0].line;
            }else if(tgtId.indexOf('mod-parcours-line-')!= -1){
                tgtId = 'mod-parcours-line-'+this.AllRiddles.filter(v => v.id == this.draggedElementId)[0].line;
            }
        }
        //var containerSha = this.container.getElementById(tgtId).getElementsByClassName('mod-line-content')[0];
        var containerSha = this.container.querySelector('#'+tgtId).querySelectorAll('.mod-line-content')[0];
        if(this.dragOverShadow == null){
            this.dragOverShadow = document.createElement('div');
            this.dragOverShadow.id = 'drag-over-shadow';
        }
        if(containerSha != null && !containerSha.contains(this.dragOverShadow)){
            containerSha.prepend(this.dragOverShadow);
        }
    }


    createCardModification(riddle_id,isModCard,lvl){
        if ("content" in document.createElement("template")) {
            // On prépare une ligne pour le tableau
            let node = document.querySelector("#add-mod-riddles");
            this.cancelModAdd();
            let clone = document.importNode(node.content, true);
            if(isModCard){
                var riddle = this.AllRiddles.filter(v => v.id == riddle_id)[0];
                //add new Information :
                clone.querySelector("#header-add-mod-riddles").textContent = "Modifier énigme";
                clone.querySelector("input[name='id']").textContent = riddle.id;
                clone.querySelector("input[name='disabledCB']").checked = riddle.disabled;
                clone.querySelector("input[name='lvl']").textContent = riddle.line;
                clone.querySelector('.current-riddle-name').textContent = riddle.name;
                clone.querySelector('.current-riddle-descr').textContent = riddle.description;
                clone.querySelector('.current-riddle-code').textContent = riddle.code;
                clone.querySelector('.current-riddle-url').textContent = "URL";
                clone.querySelector('.current-riddle-url').href = riddle.url;
                if(riddle.post_resolution_message != null){
                    clone.querySelector('.current-riddle-post-msg').textContent = riddle.post_resolution_message;
                }else{
                    clone.querySelector('.current-riddle-post-msg').remove();
                }
                var modBtn = document.createElement('button');
                modBtn.className = "btn btn-primary validate-button my-1 center-block";
                modBtn.textContent = "Modifier";
                modBtn.addEventListener('click',ev=>this.modRiddleInfo());
                clone.querySelector('.btn-riddles').appendChild(modBtn);
            }else{
                clone.querySelector("#header-add-mod-riddles").textContent = "Ajouter énigme";
                clone.querySelector("input[name='id']").textContent = riddle_id;
                clone.querySelector("input[name='disabledCB']").checked = false;
                clone.querySelector('.current-riddle-info').remove();
                clone.querySelector("input[name='lvl']").textContent = lvl;
                var addBtn = document.createElement('button');
                addBtn.className = "btn btn-primary validate-button my-1 center-block";
                addBtn.textContent = "Ajouter";
                addBtn.addEventListener('click',ev=>this.addRiddleInfo());
                clone.querySelector('.btn-riddles').appendChild(addBtn);
            }

            var cancelBtn = document.createElement('button');
            cancelBtn.className = "btn btn-danger";
            cancelBtn.textContent = "Annuler";
            cancelBtn.addEventListener('click',ev=>this.cancelModAdd());
            clone.querySelector('.btn-riddles').appendChild(cancelBtn);
            clone.querySelector(".card-admin").id = "add-mod-riddles-node";
            this.container.querySelector("#parcour-mod-div").style.opacity = 0.15;
            this.container.appendChild(clone);
        }
    }

    addRiddleInfo(){
        this.container.querySelector("#parcour-mod-div").style.opacity = 1;
        var clone = this.container.querySelector("#add-mod-riddles-node");
        if(clone != null) {
            var newId = 1;
            while(this.AllRiddles.filter(r => r.id == newId).length!= 0)
                newId++;
            var oldRiddle = JSON.parse(JSON.stringify( this.AllRiddles[0]));
            oldRiddle.name = "";
            oldRiddle.description = "";
            oldRiddle.code = "";
            oldRiddle.url = "";
            oldRiddle.disabled = false;
            oldRiddle.post_resolution_message = "";
            oldRiddle.id = newId;
            var newRiddle = this.getNewRiddle(oldRiddle);
            this.cancelModAdd();
            if(newRiddle != null){
                newRiddle.disabled = (newRiddle.disabled)?"true":"false";
                $.ajax('admin/modifyRiddle', {
                    data : {riddle : newRiddle},
                    dataType: 'json',
                    success: (response) => {
                        if(response.status.type == 'success'){
                            $('#success-modal').find('.modal-message').text(response.status.message);
                            $('#success-modal').modal('show');
                            newRiddle.disabled = (newRiddle.disabled == "true");
                            this.AllRiddles.push(newRiddle);
                            this.update();
                        }else{
                            $('#error-modal').find('.modal-message').text(response.status.message);
                            $('#error-modal').modal('show');
                        }
                    }
                });
            }

        }
    }

    modRiddleInfo(){
        this.container.querySelector("#parcour-mod-div").style.opacity = 1;
        var clone = this.container.querySelector("#add-mod-riddles-node");
        if(clone != null) {
            var id = clone.querySelector("input[name='id']").textContent;
            var oldRiddle = this.AllRiddles.filter(v => v.id == id)[0];
            var newRiddle = this.getNewRiddle(oldRiddle);
            this.cancelModAdd();
            if(newRiddle != null){
                newRiddle.disabled = (newRiddle.disabled)?"true":"false";
                $.ajax('admin/modifyRiddle', {
                    data : {riddle : newRiddle},
                    dataType: 'json',
                    success: (response) => {
                        if(response.status.type == 'success'){
                            $('#success-modal').find('.modal-message').text(response.status.message);
                            $('#success-modal').modal('show');
                            newRiddle.disabled = (newRiddle.disabled == "true");
                            this.AllRiddles[this.AllRiddles.indexOf(oldRiddle)] = newRiddle;
                            this.update();
                        }else{
                            $('#error-modal').find('.modal-message').text(response.status.message);
                            $('#error-modal').modal('show');
                        }
                    }
                });
            }
        }

    }

    getNewRiddle(oldRiddle){
        var clone = this.container.querySelector("#add-mod-riddles-node");
        if(clone != null){
            var riddleTmp=JSON.parse(JSON.stringify( oldRiddle));
            var name = clone.querySelector("input[name='name']").value;
            var description = clone.querySelector("input[name='description']").value;
            var code = clone.querySelector("input[name='code']").value;
            var url = clone.querySelector("input[name='url']").value;
            var disabled = clone.querySelector("input[name='disabledCB']").checked;
            var postMessage =clone.querySelector("input[name='post-msg']").value;
            riddleTmp.name = (name != "")?name : riddleTmp.name;
            riddleTmp.description = (description != "")?description : riddleTmp.description;
            riddleTmp.code = (code != "")?code : riddleTmp.code;
            riddleTmp.url = (url != "")?url : riddleTmp.url;
            riddleTmp.disabled = disabled;
            riddleTmp.post_resolution_message = (postMessage != "")?postMessage : riddleTmp.post_resolution_message;
            riddleTmp.line = clone.querySelector("input[name='lvl']").textContent;
            return riddleTmp;
        }
        return null;
    }

    cancelModAdd(){
        var clone = this.container.querySelector("#add-mod-riddles-node");
        if(clone!= null)
            this.container.removeChild(this.container.querySelector("#add-mod-riddles-node"));
        this.container.querySelector("#parcour-mod-div").style.opacity = 1;
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
            if(riddleJSON.disabled){
                cb_activated.textContent ="Désactivée";
                cb_activated.style.color = 'red';
                cb_activated.style.font_weight = 'bold';
            }else{
                cb_activated.style.display="none";
            }
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
