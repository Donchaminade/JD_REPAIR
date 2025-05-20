<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM reparation ORDER BY date_reparation DESC");
$stmt->execute();
$reparations = $stmt->fetchAll();
?>

<div class="container mx-auto py-6 px-4">
    <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
        <button onclick="window.location.href='create.php'" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow">
            + Ajouter une réparation
        </button>

        <form id="searchForm" class="flex flex-col sm:flex-row gap-2 items-center">
            <input type="text" id="searchInput" placeholder="Rechercher par technicien..." class="px-4 py-2 bg-gray-200 text-gray-800 border border-gray-300 rounded-md" />
            <button type="submit" class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fa fa-search"></i> Rechercher
            </button>
        </form>
    </div>

    <div class="mb-4 flex gap-4">
        <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">📄 Export PDF</button>
        <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">📊 Export Excel</button>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
        <table id="reparationTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-6 py-3">ID Demande</th>
                    <th class="px-6 py-3">Technicien</th>
                    <th class="px-6 py-3">Observation</th>
                    <th class="px-6 py-3">Date réparation</th>
                    <th class="px-6 py-3 no-export">Actions</th>
                </tr>
            </thead>
            <tbody id="reparationsTable">
                <?php foreach ($reparations as $reparation): ?>
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4"><?= htmlspecialchars($reparation['id_demande']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($reparation['technicien']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($reparation['observation']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($reparation['date_reparation']) ?></td>
                        <td class="px-6 py-4 flex gap-2 no-export">
                            <button onclick='openModal(<?= json_encode($reparation) ?>)' class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                <i class="fa fa-eye"></i> Voir 
                            </button>
                            <a href="update.php?id_reparation=<?= $reparation['id_reparation'] ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs flex items-center gap-1">
                                <i class="fa fa-pen"></i> Mod
                            </a>
                            <a href="delete.php?id_reparation=<?= $reparation['id_reparation'] ?>" onclick="return confirm('Supprimer cette réparation ?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                <i class="fa fa-trash"></i> Supp
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODALE -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xl w-full relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl"><i class="fa fa-times"></i></button>
        <h2 class="text-xl font-bold mb-4 text-blue-600">Détails de la réparation</h2>
        <div id="modalContent" class="space-y-2 text-sm text-gray-800 dark:text-gray-200">
            <!-- Contenu dynamique -->
        </div>
    </div>
</div>

<!-- JS et Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function exportToPDF() {
    const doc = new jspdf.jsPDF();
    doc.text("Liste des réparations", 14, 10);

    const headers = [];
    const rows = [];

    document.querySelectorAll('#reparationTable thead tr th:not(.no-export)').forEach(th => {
        headers.push(th.innerText);
    });

    document.querySelectorAll('#reparationTable tbody tr').forEach(row => {
        const rowData = [];
        row.querySelectorAll('td:not(.no-export)').forEach((td, index) => {
            if (index < headers.length) rowData.push(td.innerText);
        });
        rows.push(rowData);
    });

    doc.autoTable({
        head: [headers],
        body: rows,
        styles: { fontSize: 8 },
        startY: 20
    });

    doc.save("reparations.pdf");
}

function exportToExcel() {
    const table = document.getElementById("reparationTable");
    const clone = table.cloneNode(true);
    clone.querySelectorAll('.no-export').forEach(el => el.remove());

    const wb = XLSX.utils.table_to_book(clone, { sheet: "Reparations" });
    XLSX.writeFile(wb, "reparations.xlsx");
}

function openModal(data) {
    const content = `
        <p><strong>ID Demande :</strong> ${data.id_demande}</p>
        <p><strong>Technicien :</strong> ${data.technicien}</p>
        <p><strong>Observation :</strong> ${data.observation}</p>
        <p><strong>Date réparation :</strong> ${data.date_reparation}</p>
    `;
    document.getElementById("modalContent").innerHTML = content;
    document.getElementById("detailModal").classList.remove("hidden");
    document.getElementById("detailModal").classList.add("flex");
}

function closeModal() {
    document.getElementById("detailModal").classList.add("hidden");
    document.getElementById("detailModal").classList.remove("flex");
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>
