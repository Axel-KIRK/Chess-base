var app = {
    /*eslint:disable*/
    init: function () {
        $('#match-making').on('click', app.matchMaking);
        $('#invitation').on('submit', app.invitation);
        $('#invitationModal').on('hidden.bs.modal', app.refuseInvitation);
        $('#invitation-ok').on('click', app.acceptInvitation);
        $('#invitation-cancel').on('click', app.cancelInvitation);
        $('#match-making-cancel').on('click', app.leaveMatchMaking);
        $("#message_receiver").chosen();


        var webSocket = WS.connect("ws://127.0.0.1:8080");

        webSocket.on("socket/connect", function (session) {
            app.session = session;
            app.invitationSubscribe();

        })

        webSocket.on("socket/disconnect", function (error) {
            //error provides us with some insight into the disconnection: error.reason and error.code
            console.log("Disconnected for " + error.reason + " with code " + error.code);
        })
    },
 
    matchMaking: function () {
        app.session.subscribe('matchmaking', function (uri, payload){ 
        
        if('undefined' !== payload.error){
            app.showMatchMakingMessage(payload.error);
        }
        if ('undefined' !== payload.confirm){
                app.showMatchMakingMessage(payload.confirm);
                app.switchMatchMakingButton();
            }
            if (typeof payload.matchFound !== 'undefined') {
                app.session.unsubscribe('matchmaking');
                location.href = BASE_URL + payload.matchFound;
            }
        });
    },
//rte
    invitationSubscribe: function () {
        app.session.subscribe("invitation", function (uri, payload) {

            if (payload.type === 'invite') {
                app.session.unsubscribe('invitation');
                $('#invitationModal').modal('show');
                $('.modal-body').text(payload.message);
                app.currentInvitationChannel = payload.channel;

                app.session.subscribe(app.currentInvitationChannel, function (uri, payload) {

                    if (payload.type === 'cancel') {
                        app.session.unsubscribe(app.currentInvitationChannel);
                        app.invitationSubscribe();
                        $('#invitationModal').modal('hide')
                    }
                })
            }
            if (payload.type === 'error') {
                app.showInviteFormMessage(payload.message);
            }

            if (payload.type === 'invitationSend') {
                app.switchFormButton();
                app.showInviteFormMessage(payload.message);
                app.session.unsubscribe("invitation");

                app.currentInvitationChannel = "invitation/" + USER_NAME + '/' + payload.receiver;

                app.session.subscribe(app.currentInvitationChannel, function (uri, payload) {

                    if (typeof payload.type !== 'undefined') {

                        if (payload.type === 'cancel') {
                            app.showInviteFormMessage('Invitation refusé');
                            app.session.unsubscribe(app.currentInvitationChannel);
                            app.invitationSubscribe();
                            app.switchFormButton();
                        }

                        if (payload.type === 'refuse') {
                            app.showInviteFormMessage('Invitation refusé');
                            app.session.unsubscribe(app.currentInvitationChannel);
                            app.invitationSubscribe();
                            app.switchFormButton();
                        }

                        if (payload.type === 'accept') {
                            app.showInviteFormMessage('Invitation accepté');
                            location.href = BASE_URL + payload.matchFound;
                        }
                    }
                });
            }
        });
    },


    invitation: function (evt) {
        evt.preventDefault();
        var receiver = $(this).find('input').val();
        app.session.publish("invitation", {
            'receiver': receiver,
            "type": "invite"
        });
    },


    acceptInvitation: function () {
        app.session.subscribe(app.currentInvitationChannel, function (uri, payload) {
            if (typeof payload.matchFound !== 'undefined') {
                location.href = BASE_URL + payload.matchFound;
            }
        });
        app.session.publish(app.currentInvitationChannel, {
            'type': 'accept'
        });
    },


    refuseInvitation: function () {
        app.session.subscribe(app.currentInvitationChannel);
        app.session.publish(app.currentInvitationChannel, {
            'type': 'refuse'
        });
        app.session.unsubscribe(app.currentInvitationChannel);
        app.invitationSubscribe();
    },

    cancelInvitation: function () {
        app.session.unsubscribe(app.currentInvitationChannel);
        app.invitationSubscribe();
        app.showInviteFormMessage('Invitation annulé');
        app.switchFormButton();
    },


    showInviteFormMessage: function ($message) {
        $('#form-message').remove();
        $('#invitation').append('<p id="form-message">' + $message + '</p>');
    },

    switchFormButton: function ($message) {
        if ($('#invitation-cancel').hasClass('d-none')) {
            $('#invitation-cancel').removeClass('d-none');
            $('#invitation-invite').addClass('d-none');
        } else {
            $('#invitation-cancel').addClass('d-none');
            $('#invitation-invite').removeClass('d-none');
        }

    },

    showMatchMakingMessage :function($message){
        $('#MatchMaking-message').remove();
        $('.buttonContainer').append('<p id="MatchMaking-message">' + $message + '</p>');
    },

    switchMatchMakingButton: function ($message) {
        if ($('#match-making-cancel').hasClass('d-none')) {
            $('#match-making-cancel').removeClass('d-none');
            $('#match-making').addClass('d-none');
        } else {
            $('#match-making-cancel').addClass('d-none');
            $('#match-making').removeClass('d-none');
        }

    },

    leaveMatchMaking : function() { 
        console.log(app.session);
        app.switchMatchMakingButton();
        $('#MatchMaking-message').remove();
        app.session.unsubscribe('matchmaking');
    }


}

$(app.init);