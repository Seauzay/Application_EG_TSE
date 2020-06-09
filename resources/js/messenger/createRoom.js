const MessageTemplate = require('./MessageTemplate').MessageTemplate;
const MessageAPI = require('./MessageAPI').MessageAPI;


class RoomList {
    constructor(tablist) {
        this.tablist = tablist;
        this.rooms = [];

    }

    addRoom(id, name) {
        if (this.rooms.indexOf(id) === -1) {
            const pos = this.tablist.addTab({title: "Messages reçus", position : 1,icon: '<svg class="bi bi-envelope-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                    '  <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>' +
                    '</svg>&nbsp;'});
            const api = createRoom(this.tablist.contentOfTab(pos + 1), id);
            api.callback = () => {
                this.tablist.notify(pos+1);
            };
            Echo.channel('application_tracking_escape_game_tse_database_message-'+id).listen('.newMessage',function(e){
                api.refreshMessages();
            });
            this.rooms.push(id);
        }

    }

    update() {
        $.ajax('msg/list', {
            success: (data) => {
                data.rooms.forEach(room => {
                    this.addRoom(room.id, room.name);

                });
            }
        });
    }
}


function createRoom(where, room_id) {
    where = $(where);
    if (!window.messageTemplate) {
        window.messageTemplate = new MessageTemplate('#message-template');
    }

    const node = $('#room-template').clone();
    const form = node.find('form');
    const new_action = form.attr('action').replace('{id}', room_id);
    form.attr('action', new_action);

    where.append(node);
    return new MessageAPI(room_id, where.find('.message-container')[0], window.messageTemplate, where.find('.message-form')[0],tablist);
}

exports.createRoom = createRoom;
exports.RoomList = RoomList;
