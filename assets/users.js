function userDelete(id) {
    if (confirm("Êtes vous sûr de vouloir supprimer l'utilisateur ?")) {
        window.location.href = "users/"+id+"/delete";
    } else {
        alert("Vous avez annulé la suppression");
    }
}

window.userDelete = userDelete;