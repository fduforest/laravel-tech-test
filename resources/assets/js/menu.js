var baseUrl = window.location.origin;
var currentUrl = window.location.href;

var Menu = {

    preload : function() {
        // Load all the needed resources for the menu.
        game.load.image('menu', baseUrl + '/images/menu.png');
    },

    create: function () {

        // Add menu screen.
        // It will act as a button to start the game.
        this.add.button(0, 0, 'menu', this.startGame, this);

    },

    startGame: function () {

        // Change the state to the actual game.
        this.state.start('Game');

    }

};