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

        this.riddleTimer = new Timer();
        this.riddleTimer.addEventListener('secondsUpdated', () => {
            this.root.find('.current-riddle-time').text(formatMS(this.riddleTimer.getTotalTimeValues().secondTenths * 100));
        });

        this.teamTimer = new Timer();
        this.teamTimer.addEventListener('secondsUpdated', () => {
            this.root.find('.team-time').text(formatMS(this.teamTimer.getTotalTimeValues().secondTenths * 100));
        });
    }

    setAtributes(options) {
        if (options.teamName)
            this.setTeamName(options.teamName);
        if (options.riddleName)
            this.setRiddleName(options.riddleName);
        if (options.progress)
            this.setProgress(options.progress);
		if (options.score)
			this.setScore(options.score);
        if (options.start && options.end) {
            if (this.teamTimer.isRunning()) {
                this.teamTimer.stop();
            }
            this.root.find('.team-time').text(formatMS(new Date(options.end) - new Date(options.start)));
        } else if (options.start) {
            if (!this.teamTimer.isRunning()) {
                const ms = Date.now() - new Date(options.start);
                const sec = Math.floor(ms / 1000);
                this.teamTimer.start({
                    startValues: {
                        seconds: sec
                    }
                });
                this.root.find('.current-riddle-time').text(formatMS(this.teamTimer.getTotalTimeValues().secondTenths * 100));
            }
        } else {
            if (this.teamTimer.isRunning()) {
                this.teamTimer.stop();
            }
            this.root.find('.team-time').text(formatMS(0));
        }
        if (options.riddle_start && options.riddle_end) {
            if (this.teamTimer.isRunning()) {
                this.teamTimer.stop();
            }
            this.root.find('.current-riddle-time').text(formatMS(new Date(options.riddle_end) - new Date(options.riddle_start)));
        } else if (options.riddle_start) {
            if (this.riddleTimer.isRunning())
                this.riddleTimer.stop();
            const ms = Date.now() - new Date(options.riddle_start);
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
    }

    addGMTeam(id) {
        const newDiv = $('<div>', {id: id});
        this.root.append(newDiv);
        const gmTeam = new GMTeam(newDiv, id);
        this.gmTeams.push(gmTeam);
        return gmTeam;
    }

    updateTeams(teamJSON) {
        const names = teamJSON.riddle_names;
        const data = teamJSON.data
		data.sort(function(a,b){return (b.team.score - a.team.score)});
        data.forEach((data) => {
            const team = data.team;
            const riddles = data.riddles;

            const prog = riddles.map(r => (r.start_date ? 1 : 0) + (r.end_date ? 1 : 0)).reduce((a, b) => a + b);

            const gmteam = this.addGMTeam('gm-team-' + team.id);
            // todo à améliorer en prenant en compte les temps (pour l'instant on prend la dernière)
            const currentRiddle = riddles.pop();
            gmteam.setAtributes({
                teamName: team.name,
                riddleName: names[currentRiddle.id - 1],
                progress: 100 * prog / (teamJSON.riddle_number * 2),
                start: team.start_date,
                end: team.end_date,
                riddle_start: currentRiddle.start_date,
                riddle_end: currentRiddle.end_date,
				score: team.score
            });
            // détail
            const list = gmteam.root.find('.card-body ul');
            riddles.forEach((riddle) => {
                const content = $('<li>');
                const start = new Date(riddle.start_date);
                const end = new Date(riddle.end_date);
                content.text(names[riddle.id - 1] + ' en ' + formatMS(end - start));
                list.append(content);
            });
        });
    }

    update() {
        $.ajax('riddleteam/list', {method: 'GET', success: (response) => this.updateTeams(response)});
    }
}

exports.GMTeam = GMTeam;
exports.GMTeamList = GMTeamList;