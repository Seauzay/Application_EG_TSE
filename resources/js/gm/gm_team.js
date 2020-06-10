const {Timer} = require('easytimer.js');
var moment = require('moment');

function dateNow(){
    let date;
    $.ajax('whatistimenow/', {
        method: 'GET',
        async : false,
        dataType: 'json',
        success: function(data) {date=data.now.date;}
    });
    return moment(date,"YYYY-MM-DD hh:mm:ss");
}

function formatMS(s) {
    function pad(n, z) {
        z = z || 2;
        return ('00' + n).slice(-z);
    }

    const ms = s % 1000;
    s = (s - ms) / 1000;
    const secs = s % 60;
    s = (s - secs) / 60;
    const mins = s % 60;
    const hrs = (s - mins) / 60;
    return (hrs > 0 ? pad(hrs) + ':' : '') + pad(mins) + ':' + pad(secs) /*+ '.' + pad(ms, 3)*/;
}

//fonction atribuant un classement à partir d'un entier
// 1 -> 1er, 2->2nd, 3->3eme
function classe(int){
	const nb = int;
	switch(nb){
		case 1: return ('1er');
				break;
		case 2: return ('2nd');
				break;
		default: return (int+'eme');
				break;
	}
}

function isValidProperty(object,property){
    return object.hasOwnProperty(property) && object[property] != undefined;
}

const GMTeamFactory = (function () {
    const class_prefix = 'gm-team';
    const accordion_prefix = class_prefix + '-accordion-';
    const heading_prefix = class_prefix + '-heading-';
    const collapse_prefix = class_prefix + '-collapse-';

    return {
        construct: function (root, id) {
            this.accordionID = accordion_prefix + id;
            this.headingID = heading_prefix + id;
            this.collapseID = collapse_prefix + id;

            // fills the node
            const template = $('#gm-team-template');
            if (!template.exists())
                throw Error('gm-team-template does not exist');
            template.clone().appendTo(root);

            // adds ids
            $(root).find('.card').attr('id', this.accordionID);
            $(root).find('.card > .card-header').attr('id', this.headingID);
            $(root).find('.card > .collapse').attr('id', this.collapseID);

            // adds attributes
            $('#' + this.headingID).find('span').attr('data-target', '#' + this.collapseID)
                .attr('aria-expanded', 'false')
                .attr('aria-controls', this.collapseID);

            $('#' + this.collapseID).attr('aria-labelledby', this.headingID)
                .attr('data-parent', '#' + this.accordionID);

            // adds click listener to open
            $('#' + this.headingID).click(function () {
                $(this).parent().find('.collapse').collapse('toggle');
            });

            // adds animation on opening
            $('#' + this.accordionID).on('show.bs.collapse', function () {
                $(root).find(".card-header > i").addClass('active');
            }).on('hide.bs.collapse', function () {
                $(root).find(".card-header > i").removeClass('active');
            });

            return {
                accordion: this.accordionID,
                heading: this.headingID,
                collapse: this.collapseID
            }
        }
    };
})();

//Classe encapsulant les données et fonctions liées à l'affichage de
//l'avancement d'une équipe
class GMTeam {
    constructor(root, id) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }
        this.root = root;

        // saves id
        this.id = id;

        // constructs and retrieves ids
        this.ids = GMTeamFactory.construct(root, id);

        this.classement = null;

        this.riddleTimer = new Timer();
        this.riddleTimer.addEventListener('secondsUpdated', () => {
            this.root.find('.current-riddle-time').text(formatMS(this.riddleTimer.getTotalTimeValues().secondTenths * 100));
        });

        this.teamTimer = new Timer();
        this.teamTimer.addEventListener('secondsUpdated', () => {
            this.root.find('.team-time').text('Temps écoulé: '+formatMS(this.teamTimer.getTotalTimeValues().secondTenths * 100));
        });
    }
	//fonction permettant de modifier plusieurs information pour
	//une équipe en même temps
    setAtributes(options) {
        if (isValidProperty(options,'teamName'))
            this.setTeamName(options.teamName);
        if (isValidProperty(options,'riddleName'))
            this.setRiddleName(options.riddleName);
        if (isValidProperty(options,'progress'))
            this.setProgress(options.progress);
		if (isValidProperty(options,'score'))
			this.setScore(options.score);
		if (isValidProperty(options,'classement'))
			this.setClassement(options.classement);
        console.log(options.start);
        if (isValidProperty(options,'start') && isValidProperty(options,'end')) {
            if (this.teamTimer.isRunning()) {
                this.teamTimer.stop();
            }
            this.root.find('.team-time').text('Temps écoulé: '+formatMS(moment(options.end.date,"YYYY-MM-DD hh:mm:ss").diff(moment(options.start.date,"YYYY-MM-DD hh:mm:ss"))));
        } else if (isValidProperty(options,'start')) {
            if (!this.teamTimer.isRunning()) {
                const ms = dateNow().diff(moment(options.start.date,"YYYY-MM-DD hh:mm:ss"));
                const sec = Math.floor(ms / 1000);
                this.teamTimer.start({
                    startValues: {
                        seconds: sec
                    }
                });
                this.root.find('.team-time').text('Temps écoulé: '+formatMS(this.teamTimer.getTotalTimeValues().secondTenths * 100));
            }
        } else {
            if (this.teamTimer.isRunning()) {
                this.teamTimer.stop();
            }
            this.root.find('.team-time').text('Temps écoulé: '+formatMS(0));
        }
        if (isValidProperty(options,'riddle_start') && isValidProperty(options,'riddle_end')) {
            if (this.riddleTimer.isRunning()) {
                this.riddleTimer.stop();
            }
            this.root.find('.current-riddle-time').text(formatMS(moment(options.riddle_end,"YYYY-MM-DD hh:mm:ss").diff(moment(options.riddle_start,"YYYY-MM-DD hh:mm:ss"
            ))));
        } else if (isValidProperty(options,'riddle_start')) {
            if (this.riddleTimer.isRunning()) {
                this.riddleTimer.stop();
            }
            const ms = dateNow().diff(moment(options.riddle_start,"YYYY-MM-DD hh:mm:ss"));
            const sec = Math.floor(ms / 1000);
            this.riddleTimer.start({
                startValues: {
                    seconds: sec
                }
            });
            this.root.find('.current-riddle-time').text(formatMS(this.riddleTimer.getTotalTimeValues().secondTenths * 100));
        } else {
            if (this.riddleTimer.isRunning()) {
                this.riddleTimer.stop();
            }
            this.root.find('.current-riddle-time').text(formatMS(0));
        }
    }

    setTeamName(str) {
        this.root.find('.team-name').text(str);
    }

    setRiddleName(str) {
        this.root.find('.current-riddle').text(str);
    }

	setScore(int){
		this.root.find('.team-score').text(int);
	}

	setClassement(int){
		this.root.find('.classement').text(classe(int));
		this.classement = int;
	}

    setProgress(input) {
        let n;
        if ((typeof input) === 'string') {
            n = parseFloat(input.match(/(([0-9]*[.])?[0-9]+)/)[0]);
            if (!/%/.test(input)) {
                n *= 100;
            }
        } else {
            n = input;
        }
        n = Math.min(Math.max(n, 0), 100);
        this.root.find('.progress-bar').attr('style', 'width: ' + +n + '%').attr('aria-valuenow', n);
    }
}

//Fonction encapsulant l'ensemble des équipes ayant commencé le jeu.
class GMTeamList {
    constructor(root) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }
        this.root = root;

        this.gmTeams = [];

        //Temporary until implementing broadcasting.
        this.refreshTimeout = null;
        this.refreshDelay = 10000;
    }
	//Ajoute une équipe à GM, provoque l'affichage de l'équipe sur la page.
    addGMTeam(id) {
        const newDiv = $('<div>', {id: id});
        newDiv.hide().appendTo(this.root).fadeIn(500);
        const gmTeam = new GMTeam(newDiv, id);
        this.gmTeams.push(gmTeam);
        return gmTeam;
    }

    //Select a GM. If not found, creates a new one.
    findOrCreateGMTeam(id) {
        for (const gmTeam of this.gmTeams){
            if (gmTeam.id == id)
                return gmTeam;
        }
        return this.addGMTeam(id);
    }

    updateClassement() {

        this.gmTeams.sort((a,b)=>a.classement-b.classement)
        const root = this.root;
        const gmTeams = this.gmTeams;
        let tempHeight = root.height();
        root.height(tempHeight);
        for (let i = root.children().length - 1; i >= 0; i--){
            const div = root.children()[i];
            if( i == 0){
                $('#'+div.id).delay(500*(root.children().length-1)).fadeOut(500,function(){
                    $(this).detach();
                    for (let j = gmTeams.length-1; j >=0 ; j--){
                        if (j == 0){
                            gmTeams[gmTeams.length-1].root.appendTo(root).delay(500*(gmTeams.length-1)).fadeIn(500, function(){
                                root[0].style.height = null;
                            });
                        }else{
                            gmTeams[gmTeams.length-1-j].root.appendTo(root).delay(500*(gmTeams.length-1-j)).fadeIn(500);
                        }

                    }
                });
            }else {
                $('#' + div.id).delay(500*(root.children().length-1-i)).fadeOut(500, function () {
                    $(this).detach();
                });
            }
        }
    }

	//Fonction mettant à jour l'affichage des équipes classé.
	//Si jamais cette fonction était appelée autrement qu'en mettant toute la page web à jour (refresh)
	//Il faudra probablement vider la liste gmTeams à chaque appel pour que le classment se mette à jour.
    updateTeams(teamJSON) {
        const data = teamJSON.data
        data.sort(function(a,b){return (b.team.score - a.team.score)});
		//pos,posegal,scoreprec servent a afficher la place de l'équipe en prenant en compte les égalités.
		let pos = 0;
		let posegal = 1;
        let lastScore = 10000000000;
        let updateClassement = false;
		data.forEach((data) => {
			if (data.team.score == lastScore){
				posegal = posegal + 1;
			}
			else{
				pos = pos + posegal;
				posegal = 1;
			}
            lastScore = data.team.score;
            const team = data.team;
            const riddles = data.riddles;
            const prog = team.progression;
            const gmteam = this.findOrCreateGMTeam('gm-team-' + team.id);
            if (gmteam.classement != pos && gmteam.classement != null)
                updateClassement = true;
            // todo à améliorer en prenant en compte les temps (pour l'instant on prend la dernière)
            const currentRiddle = riddles.pop();
            gmteam.setAtributes({
                teamName: team.name,
                riddleName: currentRiddle.name,
                progress: 100 * prog,
                start: team.start_date,
                end: team.end_date,
                riddle_start: currentRiddle.start_date,
                riddle_end: currentRiddle.end_date,
				score: team.score,
				classement: pos
            });

            // This handles the details section for each teams.
            const list = gmteam.root.find('.card-body ul');
            list.empty();
            riddles.forEach((riddle) => {
                const content = $('<li>');
                const start = moment(riddle.start_date,"YYYY-MM-DD hh:mm:ss");
                const end = moment(riddle.end_date,"YYYY-MM-DD hh:mm:ss");
                content.text(riddle.name + ' en ' + formatMS(end.diff(start)));
                list.append(content);
            });
        });
		if(updateClassement)
            this.updateClassement();
    }

    update() {
        $.ajax('riddleteam/list', {method: 'GET', success: (response) => this.updateTeams(response)});
    }
}

exports.GMTeam = GMTeam;
exports.GMTeamList = GMTeamList;
