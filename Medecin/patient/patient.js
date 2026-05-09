// Liste patients - module medecin

const PATIENTS_DATA = [
    {
        id: 1,
        prenom: "Konan",
        nom: "Kouassi",
        email: "konan.k@email.com",
        tel: "+225 07 12 34 56",
        age: 34,
        sexe: "M",
        blood: "A+",
        allergies: "Penicilline",
        derniere_consultation: "2026-05-02",
        statut: "Actif",
        initiales: "KK"
    },
    {
        id: 2,
        prenom: "Awa",
        nom: "Traore",
        email: "awa.t@email.com",
        tel: "+225 05 98 76 54",
        age: 28,
        sexe: "F",
        blood: "B+",
        allergies: "Aucune",
        derniere_consultation: "2026-04-24",
        statut: "Actif",
        initiales: "AT"
    },
    {
        id: 3,
        prenom: "Jean",
        nom: "Bamba",
        email: "jean.b@email.com",
        tel: "+225 01 23 45 67",
        age: 52,
        sexe: "M",
        blood: "O+",
        allergies: "Arachides",
        derniere_consultation: "2026-03-18",
        statut: "En observation",
        initiales: "JB"
    },
    {
        id: 4,
        prenom: "Fatou",
        nom: "Diallo",
        email: "fatou.d@email.com",
        tel: "+225 07 65 43 21",
        age: 19,
        sexe: "F",
        blood: "AB+",
        allergies: "Aucune",
        derniere_consultation: "2026-04-29",
        statut: "Actif",
        initiales: "FD"
    },
    {
        id: 5,
        prenom: "Moussa",
        nom: "Coulibaly",
        email: "moussa.c@email.com",
        tel: "+225 05 11 22 33",
        age: 67,
        sexe: "M",
        blood: "O-",
        allergies: "Sulfamides",
        derniere_consultation: "2026-02-14",
        statut: "En observation",
        initiales: "MC"
    },
    {
        id: 6,
        prenom: "Amina",
        nom: "Ouedraogo",
        email: "amina.o@email.com",
        tel: "+225 07 55 44 33",
        age: 41,
        sexe: "F",
        blood: "A-",
        allergies: "Lactose",
        derniere_consultation: "2026-05-06",
        statut: "Actif",
        initiales: "AO"
    },
    {
        id: 7,
        prenom: "Koffi",
        nom: "Assi",
        email: "koffi.a@email.com",
        tel: "+225 01 77 88 99",
        age: 23,
        sexe: "M",
        blood: "B-",
        allergies: "Aucune",
        derniere_consultation: "2026-01-20",
        statut: "Inactif",
        initiales: "KA"
    },
    {
        id: 8,
        prenom: "Marie",
        nom: "Yao",
        email: "marie.yao@email.com",
        tel: "+225 05 33 22 11",
        age: 36,
        sexe: "F",
        blood: "O+",
        allergies: "Ibuprofene",
        derniere_consultation: "2026-04-12",
        statut: "Actif",
        initiales: "MY"
    },
    {
        id: 9,
        prenom: "Ibrahim",
        nom: "Sanogo",
        email: "ibrahim.s@email.com",
        tel: "+225 07 44 55 66",
        age: 45,
        sexe: "M",
        blood: "A+",
        allergies: "Aucune",
        derniere_consultation: "2026-05-01",
        statut: "Actif",
        initiales: "IS"
    },
    {
        id: 10,
        prenom: "Cecile",
        nom: "Ake",
        email: "cecile.ake@email.com",
        tel: "+225 01 99 88 77",
        age: 30,
        sexe: "F",
        blood: "AB-",
        allergies: "Gluten",
        derniere_consultation: "2026-03-11",
        statut: "Actif",
        initiales: "CA"
    }
];

let currentPage = 1;
const ITEMS_PER_PAGE = 8;
let filteredPatients = [...PATIENTS_DATA];

document.addEventListener("DOMContentLoaded", function () {
    updateStats();
    renderTable();

    const modal = document.getElementById("modal-nouveau-patient");
    if (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) closeModalNouveauPatient();
        });
    }
});

function updateStats() {
    setText("patients-count", `${PATIENTS_DATA.length} patients enregistres`);
    setText("stat-total", PATIENTS_DATA.length);

    const nouveaux = PATIENTS_DATA.filter(function (patient) {
        const consultation = new Date(patient.derniere_consultation + "T00:00:00");
        const now = new Date();
        return consultation.getMonth() === now.getMonth() &&
            consultation.getFullYear() === now.getFullYear();
    }).length;

    setText("stat-nouveaux", nouveaux);
}

function renderTable() {
    const tbody = document.getElementById("patients-tbody");
    if (!tbody) return;

    tbody.innerHTML = "";
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const pageItems = filteredPatients.slice(start, end);

    if (pageItems.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:var(--color-text-muted);">
                    Aucun patient trouve pour ces criteres.
                </td>
            </tr>`;
        setText("table-count", "0 resultat");
        setText("pagination-info", "Aucun resultat");
        setHtml("pagination-buttons", "");
        return;
    }

    pageItems.forEach(function (patient) {
        const tr = document.createElement("tr");
        const avatarStyle = patient.sexe === "F"
            ? "background:#FCE7F3;color:#9D174D;"
            : "background:#EFF6FF;color:#1D4ED8;";

        tr.innerHTML = `
            <td>
                <div class="patient-info">
                    <div class="avatar" style="${avatarStyle}">${escapeHtml(patient.initiales)}</div>
                    <div>
                        <div class="patient-name">${escapeHtml(patient.prenom)} ${escapeHtml(patient.nom)}</div>
                        <div class="patient-email">${escapeHtml(patient.email)}</div>
                    </div>
                </div>
            </td>
            <td>${patient.age} ans</td>
            <td><span class="badge badge-blood">${escapeHtml(patient.blood)}</span></td>
            <td>${escapeHtml(patient.tel)}</td>
            <td>${formatDate(patient.derniere_consultation)}</td>
            <td><span class="badge ${getBadgeClass(patient.statut)}">${escapeHtml(patient.statut)}</span></td>
            <td>
                <a href="patient-fiche.html?id=${patient.id}" class="btn-secondary" style="font-size:0.78rem;padding:6px 12px;">
                    Voir fiche
                </a>
            </td>`;
        tbody.appendChild(tr);
    });

    setText("table-count", `(${filteredPatients.length} patient${filteredPatients.length > 1 ? "s" : ""})`);
    setText("pagination-info", `Affichage ${start + 1}-${Math.min(end, filteredPatients.length)} sur ${filteredPatients.length}`);
    renderPagination();
}

function renderPagination() {
    const container = document.getElementById("pagination-buttons");
    if (!container) return;

    container.innerHTML = "";
    const totalPages = Math.max(1, Math.ceil(filteredPatients.length / ITEMS_PER_PAGE));

    container.appendChild(pageButton("<", currentPage - 1, currentPage === 1));
    for (let page = 1; page <= totalPages; page++) {
        const btn = pageButton(String(page), page, false);
        if (page === currentPage) btn.classList.add("active");
        container.appendChild(btn);
    }
    container.appendChild(pageButton(">", currentPage + 1, currentPage === totalPages));
}

function pageButton(label, page, disabled) {
    const button = document.createElement("button");
    button.className = "page-btn";
    button.textContent = label;
    button.disabled = disabled;
    button.addEventListener("click", function () {
        goToPage(page);
    });
    return button;
}

function goToPage(page) {
    const totalPages = Math.max(1, Math.ceil(filteredPatients.length / ITEMS_PER_PAGE));
    if (page < 1 || page > totalPages) return;

    currentPage = page;
    renderTable();

    const table = document.querySelector(".table-card");
    if (table) table.scrollIntoView({ behavior: "smooth", block: "start" });
}

function filterPatients() {
    const query = getValue("search-input").toLowerCase().trim();
    const sexe = getValue("filter-sexe");
    const blood = getValue("filter-blood");
    const status = getValue("filter-status");

    filteredPatients = PATIENTS_DATA.filter(function (patient) {
        const searchable = `${patient.prenom} ${patient.nom} ${patient.email} ${patient.tel}`.toLowerCase();
        return (!query || searchable.includes(query)) &&
            (!sexe || patient.sexe === sexe) &&
            (!blood || patient.blood === blood) &&
            (!status || patient.statut === status);
    });

    currentPage = 1;
    renderTable();
}

function resetFilters() {
    ["search-input", "filter-sexe", "filter-blood", "filter-status"].forEach(function (id) {
        const element = document.getElementById(id);
        if (element) element.value = "";
    });
    filteredPatients = [...PATIENTS_DATA];
    currentPage = 1;
    renderTable();
}

function openModalNouveauPatient() {
    const modal = document.getElementById("modal-nouveau-patient");
    if (modal) modal.classList.add("open");
}

function closeModalNouveauPatient() {
    const modal = document.getElementById("modal-nouveau-patient");
    if (modal) modal.classList.remove("open");

    document.querySelectorAll("#modal-nouveau-patient input, #modal-nouveau-patient select")
        .forEach(function (element) {
            element.value = "";
        });
}

function saveNouveauPatient(event) {
    event.preventDefault();

    const prenom = getValue("p-prenom").trim();
    const nom = getValue("p-nom").trim();
    const dob = getValue("p-dob");
    const sexe = getValue("p-sexe");

    if (!prenom || !nom || !dob || !sexe) {
        showToast("Veuillez remplir les champs obligatoires.", "error");
        return;
    }

    const newPatient = {
        id: PATIENTS_DATA.length + 1,
        prenom,
        nom,
        email: getValue("p-email").trim() || "-",
        tel: getValue("p-tel").trim() || "-",
        age: calcAge(dob),
        sexe,
        blood: getValue("p-blood") || "-",
        allergies: getValue("p-allergies").trim() || "Aucune",
        derniere_consultation: new Date().toISOString().split("T")[0],
        statut: "Actif",
        initiales: `${prenom.charAt(0)}${nom.charAt(0)}`.toUpperCase()
    };

    PATIENTS_DATA.unshift(newPatient);
    filteredPatients = [...PATIENTS_DATA];
    currentPage = 1;

    closeModalNouveauPatient();
    updateStats();
    renderTable();
    showToast(`Patient ${prenom} ${nom} ajoute avec succes.`, "success");
}

function formatDate(isoDate) {
    if (!isoDate) return "-";
    return new Date(isoDate + "T00:00:00").toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "short",
        year: "numeric"
    });
}

function calcAge(dateNaissance) {
    const birthDate = new Date(dateNaissance + "T00:00:00");
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

function getBadgeClass(statut) {
    if (statut === "Actif") return "badge-active";
    if (statut === "En observation") return "badge-warning";
    return "badge-inactive";
}

function showToast(message, type) {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = "toast " + (type || "success");
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(function () {
        toast.style.opacity = "0";
        toast.style.transition = "opacity 0.25s ease";
        setTimeout(function () {
            toast.remove();
        }, 250);
    }, 3000);
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function getValue(id) {
    const element = document.getElementById(id);
    return element ? element.value : "";
}

function setText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value;
}

function setHtml(id, value) {
    const element = document.getElementById(id);
    if (element) element.innerHTML = value;
}
