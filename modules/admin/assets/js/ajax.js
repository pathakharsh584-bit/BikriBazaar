/* TOAST SYSTEM */

function showToast(message, type = "success"){

    const toastContainer =
        document.getElementById("toastContainer");

    const toast = document.createElement("div");

    toast.className = `toast ${type}`;

    toast.innerHTML = `

        <i class="fa-solid ${
            type === "success"
            ? "fa-circle-check"
            : "fa-circle-xmark"
        }"></i>

        <span>${message}</span>

    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {

        toast.remove();

    }, 3000);

}

/* DELETE PRODUCT */

document.addEventListener("click", function(e){

    const deleteBtn = e.target.closest(".delete-btn");

    if(!deleteBtn) return;

    const productId = deleteBtn.dataset.id;

    const confirmDelete = confirm(
        "Are you sure you want to move this advertisement to trash?"
    );

    if(!confirmDelete) return;

    deleteBtn.disabled = true;

    fetch("./../modules/admin/ajax/delete_product.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: `product_id=${productId}`

    })

    .then(response => response.text())

    .then(data => {

        if(data.trim() === "success"){

            const row = deleteBtn.closest("tr");

            row.style.transition = "all 0.3s ease";
            row.style.opacity = "0";
            row.style.transform = "translateX(20px)";

            setTimeout(() => {

                row.remove();

            }, 300);

            showToast("Advertisement moved to trash successfully");

        }else{

            deleteBtn.disabled = false;

            showToast("Failed to delete advertisement","error");

        }

    })

    .catch(error => {

        console.error(error);

        deleteBtn.disabled = false;

        showToast("Something went wrong","error");

    });

});

/* RESTORE PRODUCT */

document.addEventListener("click", function(e){

    const restoreBtn = e.target.closest(".restore-btn");

    if(!restoreBtn) return;

    const productId = restoreBtn.dataset.id;

    const confirmRestore = confirm(
        "Restore this product?"
    );

    if(!confirmRestore) return;

    fetch("./../modules/admin/ajax/restore_product.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: `product_id=${productId}`

    })

    .then(response => response.text())

    .then(data => {

        if(data.trim() === "success"){

            const row = restoreBtn.closest("tr");

            row.remove();

            showToast("Product restored successfully");

        }else{

            showToast("Failed to restore product","error");

        }

    })

    .catch(error => {

        console.error(error);

        showToast("Something went wrong","error");

    });

});

/* EDIT MODAL */

const editModal = document.getElementById("editModal");

const closeModalBtn = document.querySelector(".close-modal");

document.addEventListener("click", function(e){

    const editBtn = e.target.closest(".edit-btn");

    if(!editBtn) return;

    editModal.classList.add("active");

    document.getElementById("editProductId").value =
        editBtn.dataset.id;

    document.getElementById("editTitle").value =
        editBtn.dataset.title;

    document.getElementById("editPrice").value =
        editBtn.dataset.price;

    document.getElementById("editCategory").value =
        editBtn.dataset.category;

    document.getElementById("editStatus").value =
        editBtn.dataset.status;

    document.getElementById("editBoost").value =
        editBtn.dataset.boost;

});

/* CLOSE MODAL */

if(closeModalBtn){

    closeModalBtn.addEventListener("click", () => {

        editModal.classList.remove("active");

    });

}

window.addEventListener("click", (e) => {

    if(e.target === editModal){

        editModal.classList.remove("active");

    }

});

/* SAVE EDIT PRODUCT */

const editForm = document.getElementById("editProductForm");

if(editForm){

    editForm.addEventListener("submit", function(e){

        e.preventDefault();

        const formData = new FormData(editForm);

        fetch("./../modules/admin/ajax/update_product.php", {

            method: "POST",

            body: formData

        })

        .then(response => response.text())

        .then(data => {

            if(data.trim() === "success"){

                showToast("Product updated successfully");

                setTimeout(() => {

                    location.reload();

                }, 1200);

            }else{

                showToast("Failed to update product","error");

            }

        })

        .catch(error => {

            console.error(error);

            showToast("Something went wrong","error");

        });

    });

}
/* RECENT ACTIVITIES AJAX */

function loadRecentActivities(){

    const activityBox =

    document.getElementById(
        "recentActivities"
    );

    if(!activityBox) return;

    fetch(

        "./../modules/admin/activity_logs/fetch_recent_activities.php"

    )

    .then(response => response.text())

    .then(data => {

        activityBox.innerHTML = data;

    })

    .catch(error => {

        console.error(
            "Activity AJAX Error:",
            error
        );

    });

}

/* INITIAL LOAD */

loadRecentActivities();

/* AUTO REFRESH */

setInterval(

    loadRecentActivities,

    5000

);
