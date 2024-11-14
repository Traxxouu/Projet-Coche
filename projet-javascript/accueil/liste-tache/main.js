document.addEventListener("DOMContentLoaded", () => {
    //-------------[Récupération des éléments du DOM - Cour page 157]----------------
    const taskInput = document.getElementById("new-task");
    const dateInput = document.getElementById("new-task-date");
    const addTaskBtn = document.getElementById("add-task-btn");
    const taskListElement = document.getElementById("task-list");
    const progressBar = document.getElementById("progress-bar");
    const listTitleElement = document.getElementById("list-title");
    let currentFilter = "all"; // Par défaut, afficher toutes les tâches

    // Charger les tâches au démarrage
    loadTasks();

    //-------------[Fonctions]----------------

    // Fonction pour charger les tâches depuis le serveur
    function loadTasks() {
        fetch(`../../api/lists/get_list.php?id=${currentListId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    listTitleElement.textContent = data.list.name;
                    renderTasks(data.tasks);
                    updateProgressBar(data.tasks);
                } else {
                    taskListElement.innerHTML = `<p>${data.error}</p>`;
                }
            })
            .catch(() => {
                taskListElement.innerHTML = `<p>Erreur lors du chargement des tâches.</p>`;
            });
    }

    // Fonction pour afficher les tâches en fonction du filtre actuel
    function renderTasks(tasks) {
        taskListElement.innerHTML = "";
        tasks
            .filter(task => {
                if (currentFilter === "completed") return task.completed;
                if (currentFilter === "in-progress") return !task.completed;
                return true; // Affiche toutes les tâches pour le filtre "all"
            })
            .forEach(task => {
                const li = document.createElement("li");
                li.setAttribute("data-id", task.id);
                li.innerHTML = `
                    <div class="task ${task.completed ? "completed" : ""}">
                        <input type="checkbox" class="task-checkbox" ${task.completed ? "checked" : ""}>
                        <span class="task-text">${task.text}</span>
                        <span class="task-date">Date limite: ${task.date}</span>
                    </div>
                    <button class="edit-btn">Modifier</button>
                    <button class="delete-btn">Supprimer</button>
                `;
                taskListElement.appendChild(li);
            });

        // Ajouter des écouteurs sur les boutons Modifier et Supprimer
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", (event) => {
                const taskId = event.target.closest("li").getAttribute("data-id");
                editTask(taskId);
            });
        });

        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", (event) => {
                const taskId = event.target.closest("li").getAttribute("data-id");
                deleteTask(taskId);
            });
        });

        // Ajouter un écouteur sur chaque case à cocher pour mettre à jour la barre de progression
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                const taskId = event.target.closest("li").getAttribute("data-id");
                const isCompleted = event.target.checked;
                updateTaskCompletion(taskId, isCompleted);
            });
        });
    }

    // Fonction pour mettre à jour la barre de progression
    function updateProgressBar(tasks) {
        const totalTasks = tasks.length;
        const completedTasks = tasks.filter(task => task.completed).length;

        // Calculer le pourcentage de progression
        const progressPercent = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;

        // Mettre à jour la largeur de la barre de progression
        progressBar.style.width = `${progressPercent}%`;
    }

    // Fonction pour marquer une tâche comme terminée ou non dans la base de données
    function updateTaskCompletion(taskId, isCompleted) {
        fetch("../../api/tasks/update.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: taskId, completed: isCompleted })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks(); // Recharger les tâches pour mettre à jour la barre de progression
            } else {
                alert("Erreur lors de la mise à jour de la tâche : " + data.error);
            }
        })
        .catch(() => alert("Une erreur est survenue lors de la mise à jour de la tâche."));
    }

    // Fonction pour modifier une tâche
    function editTask(taskId) {
        const newTaskText = prompt("Modifier la tâche :");
        const newTaskDate = prompt("Modifier la date de la tâche :");

        if (newTaskText && newTaskDate) {
            fetch("../../api/tasks/update.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: taskId, text: newTaskText.trim(), date: newTaskDate.trim() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTasks();
                } else {
                    alert("Erreur lors de la modification de la tâche: " + data.error);
                }
            })
            .catch(() => alert("Erreur lors de la modification de la tâche."));
        }
    }

    // Fonction pour supprimer une tâche avec animation
    function deleteTask(taskId) {
        const taskElement = document.querySelector(`li[data-id='${taskId}']`);

        if (taskElement && confirm("Êtes-vous sûr de vouloir supprimer cette tâche ?")) {
            taskElement.classList.add("deleting");

            setTimeout(() => {
                fetch("../../api/tasks/delete.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: taskId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadTasks();
                    } else {
                        alert("Erreur lors de la suppression de la tâche: " + data.error);
                    }
                })
                .catch(() => alert("Une erreur est survenue lors de la suppression de la tâche."));
            }, 300);
        }
    }

    //-------------[Écouteurs d'événements]----------------

    // Écouteurs de filtre
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            currentFilter = e.target.dataset.filter;
            document.querySelectorAll(".filter-btn").forEach(button => button.classList.remove("active"));
            e.target.classList.add("active");
            loadTasks(); // Recharger les tâches avec le filtre appliqué
        });
    });

    // Ajouter une tâche
    addTaskBtn.addEventListener("click", () => {
        const taskText = taskInput.value.trim();
        const taskDate = dateInput.value.trim();

        if (taskText === "") {
            alert("Veuillez entrer une description pour la tâche.");
            return;
        }

        fetch("../../api/tasks/create.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ list_id: currentListId, text: taskText, date: taskDate })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks();  // Recharger les tâches et mettre à jour la progression
                taskInput.value = "";  // Réinitialiser les champs
                dateInput.value = "";
            } else {
                alert("Erreur lors de la création de la tâche : " + data.error);
            }
        })
        .catch(error => {
            console.error("Erreur réseau ou de traitement:", error);
            alert("Une erreur est survenue lors de la création de la tâche.");
        });
    });
});
