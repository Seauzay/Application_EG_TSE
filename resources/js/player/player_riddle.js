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

/* retire de la page tout les éléments d'une classe
Attention: si les éléments en question sont liés à des classe,
cette relation n'est pas mise à jour et l'objet existera toujours
du point de vue de la classe.*/
function removeElementsByClass(className){
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        $(elements[0]).remove();
    }
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



const PlayerRiddleFactory = (function () {
    return {
        construct: function (root, id, url) {
            // fills the node
            const template = $('#player-riddle-template');
            if (!template.exists())
                throw Error('player-riddle-template does not exist');
            template.clone().appendTo(root);
            const playerRiddleRoot = root.find('.player-riddle-card').last();
            playerRiddleRoot.attr('id', id);
            return playerRiddleRoot;
        }
    };
})();

//classe gérant une énigme.
class PlayerRiddle {
    constructor(root, id) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of PlayerRiddle.';
            root = $(root);
        }

        // saves id
        this.id = id;

        // timer
        this.timer = new Timer();
        this.timer.addEventListener('secondsUpdated', () => {
            this.displayTimerTime();
        });

        //url
        this.hasURL = false;

        // constructs
        this.root = PlayerRiddleFactory.construct(root, id);

        // start button
        this.root.find('.start-button').click(() => {
            this.showButtons({
                start: false,
                validate: true,
                cancel: true
            });
            this.showURL(true);
            this.setTimer(0);
            this.startTimerFromDate(dateNow());
            $.ajax('riddle/' + this.id + '/start'); //TODO Error handling
            playerRiddleGrid.start();
            playerRiddleGrid.update();
        });

        $('#validation-modal').on('show.bs.modal', function (e) {

        	$('#validation-modal-code').val('');
    	});

        //  validate button modifies the modal when clicking
        this.root.find('.validate-button').click(() => {
            $('.alert').hide();
            const modal = $('#validation-modal');
            modal.find('.modal-title').text('Validez ' + this.root.find('.card-title').text() + '\u00A0:');
            const form = modal.find('form');

            form.off('submit');

            form.on('submit', (e) => {
                e.preventDefault();
                if (form.find('#validation-modal-code').val()) {
                    $.ajax('validationEnigme/validationMdp/' + this.id, {
                        data: form.serialize(),
                        success: (data) => {
                            if (data.status.type === 'success') {
                                this.timer.pause();
                                let div =document.getElementById("score");
                                div.innerHTML ='';
                                div.innerHTML = data.score+" pts";
                                this.showButtons({
                                    start: false,
                                    cancel: false,
                                    validate: false
                                });
                                this.showURL(false);
                                playerRiddleGrid.update();
                                modal.modal('hide');
								if(data.fin){
									window.location.href = 'player/endPage';
								}
                            }
                            if (data.status.type === 'error') {
                                if (data.status.display)
                                    $('.alert').show()
                                else
                                    // alert('Code invalide');
                                {
                                    $('.alert').show()
                                }
                            }
                        },
                        error: (data) => {
                            if (data.status.display)
                                alert(data.status.message);
                            else
                                alert('Une erreur est survenue');
                        }
                    });
                }
            });
        });

        // cancel button
        this.root.find('.cancel-button').click(() => {
            this.showButtons({
                start: true,
                validate: false,
                cancel: false
            });
            this.showURL(false);
            this.timer.stop();
            this.showTimer(false);
            $.ajax('riddle/' + this.id + '/cancel'); //TODO Error handling
            playerRiddleGrid.update();
        })
    }

    setAttributes(options) {
        if (options.description)
            this.setDescription(options.description);
        if (options.post_resolution_message)
            this.setPostResolutionMessage(options.post_resolution_message);
        if (options.id)
            this.setID(options.id);
        if (options.showButtons)
            this.showButtons(options.showButtons);
        if (options.showTimer)
            this.showTimer(options.showTimer);
        if (options.subtitle)
            this.setSubtitle(options.subtitle);
        if (options.title)
            this.setTitle(options.title);
        if (options.url)
            this.setURL(options.url);
    }

    setTitle(str) {
        this.root.find('.card-title').text(str);
    }

    setSubtitle(str) {
        this.root.find('.card-subtitle').text(str);
    }

    setDescription(str) {
        this.root.find('.card-text').text(str);
    }

    setPostResolutionMessage(str) {
        this.root.find('.card-post-resolution-message').html(str);
    }

    showPostResolutionMessage() {
    	this.root.find('.card-post-resolution-message').show();
    }

    setID(id) {
        this.id = id;
        this.root.find('.player-riddle-card').last().attr('id', id);
    }

    setTimer(ms) {
        this.root.find('.timer').text(formatMS(ms));
    }

    setURL(str) {
        this.root.find('.player-riddle-url').attr('href', str);
        this.hasURL = true;
    }

    showButton(option, show = true) {
        if (show) {
            this.root.find('.' + option + '-button').show();
        } else {
            this.root.find('.' + option + '-button').hide();
        }
    }

    showButtons(options) {
        Object.keys(options).forEach((key) => {
            options[key] ? this.root.find('.' + key + '-button').show() : this.root.find('.' + key + '-button').hide();
        })
    }

    showURL(show = true) {
        if (this.hasURL && show) {
            this.root.find('.player-riddle-url').show();
        } else {
            this.root.find('.player-riddle-url').hide();
        }
    }

    showTimer(show = true) {
        if (show) {
            this.root.find('.timer').show();
        } else {
            this.root.find('.timer').hide();
        }
    }

    startTimerFromDate(date) {
        if (!(moment.isMoment(date)))
            date = moment(date,"YYYY-MM-DD hh:mm:ss");
        const ms = dateNow().diff(date);
        const sec = Math.floor(ms / 1000);
        this.timer.start({
            startValues: {
                seconds: sec
            }
        });
        this.displayTimerTime();
    }

    displayTimerTime() {
        const val = this.timer.getTimeValues();
        const fields = val.hours > 0 ? ['hours'] : [];
        fields.push('minutes');
        fields.push('seconds');
        this.root.find('.timer').text(val.toString(fields));
    }
}

function countdownResult() {
    const modal = $('#tooLate');
        $('#tooLate').modal('show');

}

// classe gérant la grille d'enigme
class PlayerRiddleGrid {
    constructor(root) {
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }
        this.root = root;
        this.id = root.prop('id');

        this.playerRiddles = [];
        this.rowNumber = 0;
        this.before = 1;
        this.globalTimer = new Timer();
        this.globalTimer.addEventListener('secondsUpdated', () => {
            this.displayGlobalTimerTime(this.before);
        });
        this.globalTimer.addEventListener('targetAchieved', () => {
            this.before = 0;
            this.globalTimer.start({
                countdown: false,
                startValues: {
                    seconds: 0
                }
            });
            countdownResult();
            this.displayGlobalTimerTime(this.before);
        });
        this.started = false;
    }

	//ajoute une rangée permettant de contenir des enigmes au même niveaux dans la page
    addRow() {
        const rowNumber = this.root.children().length + 1;
        const container = $('<div>', {class: 'container-fluid jumbotron player-riddle-row'});
        container.attr('style',"margin-top: -10 !important");
        container.append($('<div>', {class: 'row justify-content-around'}));
        this.root.append(container);
        this.rowNumber++;
    }
	//ajoute une enigme dans la grille
	//Provoque l'affichage de l'enigme sur la page
    addPlayerRiddle(rowNumber, id) {
        const row = this.root.find('.player-riddle-row:nth-child(' + rowNumber + ') .row').first();
        const playerRiddleNumber = row.children().length + 1;
        const playerRiddle = new PlayerRiddle(row, id);
        this.playerRiddles.push(playerRiddle);
        return playerRiddle;
    }

	//mis a jour de la grille
	//gere l'affichage des enigmes et de leurs contenus.
	//On ne récupere qu'une énigme
    updateRiddles(riddleJSON) {
        const riddles = riddleJSON.riddles;
        this.updateTimer(riddleJSON.time);

		//suppression de l'affichage des enigmes dans la page
		removeElementsByClass("card player-riddle-card my-2");
		//suppression des enigmes de la classe
		this.playerRiddles.length = 0;

        riddles.forEach((riddle) => {
            let playerRiddle = this.playerRiddles.find((e) => {
                return e.id === riddle.id;
            });
			if (playerRiddle === undefined) {
				playerRiddle = this.addPlayerRiddle(1);
			}
			playerRiddle.setAttributes({
				id: riddle.id,
				title: riddle.name,
				description: riddle.description,
				url: riddle.url
			});
			if (riddle.start_date) {
				if (riddle.end_date) {
					const start = moment(riddle.start_date.date,"YYYY-MM-DD hh:mm:ss");
					const end = moment(riddle.end_date.date,"YYYY-MM-DD hh:mm:ss");

					playerRiddle.showButtons({
						start: false
					});
					playerRiddle.setTimer(end.diff(start));
				} else {
					playerRiddle.startTimerFromDate(riddle.start_date.date);
					playerRiddle.showButtons({start: false, validate: true, cancel: true});
					playerRiddle.showURL();
				}
			}else{
			    if(!riddle.can_start){
                    playerRiddle.showButtons({start: false, validate: false, cancel: false});
                }
            }
		});
        let progressBarVal= riddleJSON.progression*100;
        let html="<div class='progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow= '"+Math.abs(progressBarVal)+" ' aria-valuemin = '0' aria-valuemax='100' style='width:"+Math.abs(progressBarVal)+"%; background-color: #fdcc47 !important;'></div>";
        $(".progress").empty();
        $(".progress").append(html);
};

    updateTimer(time) {
        if (time.start_date && time.start_date.date && time.end_date && time.end_date.date) {
            this.started = true;
            let timebetween = 7200000 - (moment(time.end_date.date,"YYYY-MM-DD hh:mm:ss").diff(moment(time.start_date.date,"YYYY-MM-DD hh:mm:ss")));
            if (timebetween >= 0)
                $('#global-timer .time').text(formatMS(timebetween));
            else
                $('#global-timer .time').text('- '+formatMS(-timebetween));

            if (this.globalTimer.isRunning()) {
                this.globalTimer.stop();
            }
        } else if (time.start_date && time.start_date.date) {
            this.started = true;
            if (!this.globalTimer.isRunning()) {
                const ms = dateNow().diff(moment(time.start_date.date,"YYYY-MM-DD hh:mm:ss"));
                const sec = Math.floor(ms / 1000);
                if(7200 - sec > 0) {
                    this.globalTimer.start({
                        countdown: true,
                        startValues: {
                            seconds: 7200 - sec
                        }
                    });
                    this.displayGlobalTimerTime(this.before);
                } else {
                    this.before = 0;
                    this.globalTimer.start({
                        countdown: false,
                        startValues: {
                            seconds: sec - 7200
                        }
                    });
                    countdownResult();
                    this.displayGlobalTimerTime(this.before);
                }
            }

        }

    }

    start() {
        if (!this.started) {
            this.started = true;
            this.updateTimer({
            	start_date: {
            		date: dateNow()
            	}
            });
        }
    }

    displayGlobalTimerTime(before) {
        const val = this.globalTimer.getTimeValues();
        const fields = val.hours > 0 ? ['hours'] : [];
        fields.push('minutes');
        fields.push('seconds');
        if(before === 1)
            $('#global-timer .time').text(val.toString(fields));
        if(before === 0)
            $('#global-timer .time').text('- '+val.toString(fields));
    }

    update() {
        $.ajax('riddle/list', {method: 'GET', success: (response) => this.updateRiddles(response)});
    }

    waitForActivation(){
        let copyThis = this;
        $.ajax('riddle/list', {method: 'GET', success: function(response){
            let time = response.time;
            copyThis.addRow();
            if (time.start_date && time.start_date.date){
                copyThis.updateRiddles(response);
            }
            else{
                let playerRiddle = copyThis.addPlayerRiddle(1);
                playerRiddle.setAttributes({
                    title: 'Jeu en attente',
                    description: "Veuillez attendre le lancement du jeu par le game master.",
                });
                playerRiddle.showButtons({start: false, validate: false, cancel: false});
                playerRiddle.showTimer(false);
                $('#global-timer .time').text('02:00:00');
            }
        }});
    }
}

exports.PlayerRiddle = PlayerRiddle;
exports.PlayerRiddleGrid = PlayerRiddleGrid;
