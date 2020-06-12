<<!--
A copier dans les pages HTML qui veulent utiliser CreateModParcourDisp :
{{-- Template pour la modification de parcours--}}
        <div id="mod-parcour-display-template">
            <div id="mod-bdd">
                <button id="btn-mod-bdd"class="btn btn-primary validate-button my-1" onclick="modParcours(event)">Modifier</button>
                <button id="btn-reset-display"class="btn btn-primary validate-button my-1" onclick="resetParcours(event)">Reset</button>

            </div>


            <div class="mod-parcour-container">
                <template id="mod-parcour-template">
                    <div class="card-admin" draggable="true" ondragstart="drag(event)" ondragover="dragOver(event)" ondragend="dragEnd(event)">
                        <h4 class="current-riddle-name">title</h4>
                        <div class="current-riddle-activated"></div>
                        <div class="collapse-content">Détails</div>
                        <span class="id-card" hidden></span>
                        <div class="current-riddle-info">
                            <div class="current-riddle-descr">descr</div>
                            <div class="current-riddle-code">code</div>
                            <div class="current-riddle-post-msg">Msg de resolution</div>
                            <a draggable="false" class="current-riddle-url" >URL</a>
                        </div>
                    </div>
                </template>


                <div id="parcour-mod-div">
                    <ul id="possible-riddle" class="riddle-list"  ondrop="drop(event)" ondragover="allowDrop(event)">
                        <li>
                            <h2>Enigmes disponibles</h2>
                        </li>
                    </ul>

                    <ul id="mod-parcours" class="riddle-list"  ondrop="drop(event)" ondragover="allowDrop(event)">
                        <li id="header-mod-parcours">
                        </li>
                    </ul>
                </div>
            </div>
        </div>



{{-- Modification/Ajout d'énigmes  --}}
        <template id="add-mod-riddles">
                <div class="card-admin">
                    <input type="number" name="id" hidden>
                    <input type="number" name="lvl" hidden>
                    <h2 id="header-add-mod-riddles"></h2>
                        <div class="current-riddle-info">
                            <h4 class="current-riddle-name"></h4>
                            <div class="current-riddle-descr"></div>
                            <div class="current-riddle-code"></div>
                            <div class="current-riddle-post-msg" ></div>
                            <a class="current-riddle-url" >URL</a>
                        </div>
                        <div class="mod-riddle-info">
                            <input type="text" class="form-control"  name="name" placeholder="Nouveau nom">
                            <input type="text" class="form-control" name="description" placeholder="Nouvelle description">
                            <input type="text" class="form-control"  name="code" placeholder="Nouveau code">
                            <input type="text" class="form-control" name="post-msg" placeholder="Nouveau message de résolution">
                            <input type="url" class="form-control" name="url" placeholder="Nouvel URL">
                            <div class="current-riddle-disable-cb">
                                <label>Désactiver ?</label>
                                <input type="checkbox" name="disabledCB" >
                            </div>
                        </div>
                        <div class="btn-riddles"></div>
                </div>
        </template>
-->