
class DisplayEmoji {
    constructor() {

    }

    display() {
        $.ajax('player/classement', {
            method: 'GET', success: function (response) {
                if (response.rank == 1)
                    $('#emoji .rank').text('🥇');
                else if (response.rank == 2)
                    $('#emoji .rank').text('🥈');
                else if (response.rank == 3)
                    $('#emoji .rank').text('🥉');
                else
                    $('#emoji .rank').text('😖');

            }
        });
    }

}

exports.DisplayEmoji= DisplayEmoji;
