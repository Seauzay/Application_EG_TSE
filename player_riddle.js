const {Timer} = require('easytimer.js');

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


class PlayerRiddle {
    constructor(root, id) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
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
            this.showTimer();
            this.setTimer(0);
            this.startTimerFromDate(Date.now());
            $.ajax('riddle/' + this.id + '/start'); //TODO Error handling
            playerRiddleGrid.start();
        });

        $('#validation-modal').on('show.bs.modal', function (e) {
        	
        	$('#validation-modal-code').val('');
    	})
        
        //  validate button modifies the modal when clicking
        this.root.find('.validate-button').click(() => {
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
                                this.showButtons({
                                    start: false,
                                    cancel: false,
                                    validate: false
                                });
                                this.showURL(false);
                                playerRiddleGrid.update();
                                modal.modal('hide');
                            }
                            if (data.status.type === 'error') {
                                if (data.status.display)
                                    alert(data.status.message);
                                else
                                    alert('Code invalide');
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
        if (!(date instanceof Date))
            date = new Date(date);
        const ms = Date.now() - date.getTime();
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

        this.globalTimer = new Timer();
        this.globalTimer.addEventListener('secondsUpdated', () => {
            this.displayGlobalTimerTime();
        });
        this.started = false;
    }

    addRow() {
        const rowNumber = this.root.children().length + 1;
        const container = $('<div>', {class: 'container-fluid jumbotron player-riddle-row'});
        container.append($('<div>', {class: 'row justify-content-around'}));
        this.root.append(container);
        this.rowNumber++;
    }

    addPlayerRiddle(rowNumber, id) {
        const row = this.root.find('.player-riddle-row:nth-child(' + rowNumber + ') .row').first();
        const playerRiddleNumber = row.children().length + 1;
        const playerRiddle = new PlayerRiddle(row, id);
        this.playerRiddles.push(playerRiddle);
        return playerRiddle;
    }

    updateRiddles(riddleJSON) {
		this.avancement=0;
        const riddles = riddleJSON.riddles;
        this.updateTimer(riddleJSON.time);
        riddles.forEach((riddle) => {
            let playerRiddle = this.playerRiddles.find((e) => {
                return e.id === riddle.id;
            });
            if (playerRiddle === undefined) {
                if (riddle.line > this.rowNumber)
                    this.addRow();
                playerRiddle = this.addPlayerRiddle(riddle.line);
            }
            playerRiddle.setAttributes({
                id: riddle.id,
                title: riddle.name,
                description: riddle.description,
                post_resolution_message: riddle.post_resolution_message,
                url: riddle.url
            });
            if (riddle.start_date) {
                if (riddle.end_date) {
                    const start = new Date(riddle.start_date.date);
                    
                    const end = new Date(riddle.end_date.date);
                    
                    playerRiddle.showButtons({
                        start: false
                    });
					
                    playerRiddle.setTimer(end - start);
                    
                    if (riddle.post_resolution_message) {
                    	playerRiddle.showPostResolutionMessage();
                    }
                } else {
                    playerRiddle.startTimerFromDate(riddle.start_date.date);
                    playerRiddle.showButtons({start: false, validate: true, cancel: true});
                    playerRiddle.showURL();
                }
            }
        });
    
		nb_solved=0;
		nb_riddle=0;
		riddles.forEach((riddle) => {
			nb_riddle=nb_riddle+1;
			if (riddle.end_date) {
			nb_solved=nb_solved+1;}
		})
		width_val=((nb_solved)/8)*100;
		//val val=progressbar.progressbar("value")|| 0;
		$('#myBar').css("width", width_val + '%');
		//$('#avancement').progressbar({value:this.rowNumber*10});
	};

    updateTimer(time) {
        if (time.start_date && time.start_date.date && time.end_date && time.end_date.date) {
            this.started = true;
            $('#global-timer .time').text(formatMS(new Date(time.end_date.date) - new Date(time.start_date.date)));
            if (this.globalTimer.isRunning()) {
                this.globalTimer.stop();
            }
        } else if (time.start_date && time.start_date.date) {
            this.started = true;
            if (!this.globalTimer.isRunning()) {
                const ms = Date.now() - new Date(time.start_date.date);
                const sec = Math.floor(ms / 1000);
                this.globalTimer.start({
                    startValues: {
                        seconds: sec
                    }
                });
                this.displayGlobalTimerTime();
            }
        } else {
            $('#global-timer .time').text('00:00');
            if (this.globalTimer.isRunning()) {
                this.globalTimer.stop();
            }
        }
    }

    start() {
        if (!this.started) {
            this.started = true;
            this.updateTimer({
            	start_date: {
            		date: Date.now()
            	}
            });
        }
    }

    displayGlobalTimerTime() {
        const val = this.globalTimer.getTimeValues();
        const fields = val.hours > 0 ? ['hours'] : [];
        fields.push('minutes');
        fields.push('seconds');
        $('#global-timer .time').text(val.toString(fields));
		
		

    }

    update() {
        $.ajax('riddle/list', {method: 'GET', success: (response) => this.updateRiddles(response)});
    }
}

exports.PlayerRiddle = PlayerRiddle;
exports.PlayerRiddleGrid = PlayerRiddleGrid;