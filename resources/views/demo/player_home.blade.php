<script>
    // on commence par créer une PlayerRiddleGrid sur la div sélectionnée
    const playerRiddleGrid = new PlayerRiddleGrid('#mySuperRiddleGrid');
    // on ajoute une PlayerRiddle à la ligne par défaut (la numéro 1)
    const playerRiddle1_1 = playerRiddleGrid.addPlayerRiddle(1, 'playerRiddle1_1');
    // on peut modifier les attributs d'une PlayerRiddle un par un
    playerRiddle1_1.setTitle('Nom énigme 1');
    playerRiddle1_1.setSubtitle('Sous-titre');
    playerRiddle1_1.setDescription('Description : Lorem ipsum dolor sit amet, consectetur adipisicing elit.');

    // on ajoute une nouvelle ligne
    playerRiddleGrid.addRow();
    // on ajoute une nouvelle énigme à la ligne 2
    const playerRiddle2_1 = playerRiddleGrid.addPlayerRiddle(2, 'playerRiddle2_1');
    // on peut aussi modifier les atributs avec un array object
    playerRiddle2_1.setAttributes({
        title: 'Nom énigme 2',
        subtitle: 'Sous-titre',
        showTimer: true,
        showButtons: {validate: true, cancel: true, start: false},
        description: 'Description : Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
    });
    playerRiddle2_1.startTimerFromDate(Date.now());
    // ces énigmes seront vides de mots
    const playerRiddle2_2 = playerRiddleGrid.addPlayerRiddle(2, 'playerRiddle2_2');
    const playerRiddle2_3 = playerRiddleGrid.addPlayerRiddle(2, 'playerRiddle2_3');
</script>