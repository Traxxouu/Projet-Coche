document.addEventListener("DOMContentLoaded", () => {
    //-------------[Récupération des éléments du DOM]----------------------
    const listNameInput = document.getElementById("new-list-name");
    const createListBtn = document.getElementById("create-list-btn");
    const listsContainer = document.getElementById("lists");

    // Charger les listes au démarrage
    loadLists();

    //-------------[Fonction pour charger les listes depuis le serveur]----------------------
    function loadLists() {
        fetch('../api/lists/read.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderLists(data.lists); //chat function -> page word 2
                } else {
                    listsContainer.innerHTML = `<p>${data.error}</p>`;
                }
            })
            .catch(() => {
                listsContainer.innerHTML = `<p>Erreur de chargement des listes.</p>`;
            });
    }

    //-------------[Créer une nouvelle liste]----------------------
    createListBtn.addEventListener("click", () => {
        const listName = listNameInput.value.trim();
        if (listName === "") return;

        fetch('../api/lists/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: listName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLists();
                listNameInput.value = "";
            } else {
                alert('Erreur lors de la création de la liste: ' + data.error);
            }
        })
        .catch(() => alert('Erreur lors de la création de la liste.'));
    });

    //-------------[Fonction pour afficher les listes avec la barre de progression]----------------------
    function renderLists(lists) { //--> page word 2 : ChatG
        listsContainer.innerHTML = "";
        lists.forEach(list => {
            const listCard = document.createElement("div");
            listCard.classList.add("list-card");
            listCard.dataset.id = list.id;

            const totalTasks = list.total_tasks;
            const completedTasks = list.completed_tasks;
            const progress = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;

            listCard.innerHTML = `
                <h3>${list.name}</h3>
                <div class="progress-container">
                    <div class="progress-bar" style="width: ${progress}%;"></div>
                </div>
                <div class="list-buttons">
                    <a href="liste-tache/index.php?id=${list.id}" class="open-list-btn" data-id="${list.id}"><i class="fas fa-folder-open"></i> Ouvrir</a>
                    <button class="edit-list-btn" data-id="${list.id}"><i class="fas fa-edit"></i> Modifier</button>
                    <button class="delete-list-btn" data-id="${list.id}"><i class="fas fa-trash-alt"></i> Supprimer</button>
                </div>
            `;
            listsContainer.appendChild(listCard);
        });
    }

    //-------------[Gérer les actions sur les listes]----------------------
    listsContainer.addEventListener("click", (e) => {
        const listId = e.target.getAttribute("data-id");

        if (e.target.classList.contains("edit-list-btn")) {
            editList(listId);
        } else if (e.target.classList.contains("delete-list-btn")) {
            deleteList(listId);
        }
    });

    //-------------[Fonction pour éditer une liste]----------------------
    function editList(listId) {
        const newListName = prompt("Modifier le nom de la liste:");
        if (newListName && newListName.trim() !== "") {
            fetch(`../api/lists/update.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: listId, name: newListName.trim() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadLists();
                } else {
                    alert('Erreur lors de la modification de la liste: ' + data.error);
                }
            })
            .catch(() => alert('Erreur lors de la modification de la liste.'));
        }
    }

    //-------------[Fonction pour supprimer une liste]----------------------
    function deleteList(listId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette liste ?")) {
            fetch(`../api/lists/delete.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: listId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadLists();
                } else {
                    alert('Erreur lors de la suppression de la liste: ' + data.error);
                }
            })
            .catch(() => alert('Erreur lors de la suppression de la liste.'));
        }
    }
});

//-------------[Gérer l'upload du formulaire]---------------------
document.getElementById("uploadForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("upload_background.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(result => {
        alert(result);
        window.location.reload();
    })
    .catch(error => console.error("Erreur lors de l'upload:", error));
});
