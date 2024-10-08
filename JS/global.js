const logoutForm = document.querySelector(".logout-form")

document.querySelector(".hamburger-menu").addEventListener("click", () => {
    document.querySelector(".mobile-nav").classList.toggle("active");
})

document.querySelectorAll(".logout-dropdown").forEach(link => {
    link.addEventListener("click", () => {
        logoutForm.submit();
    })
});

