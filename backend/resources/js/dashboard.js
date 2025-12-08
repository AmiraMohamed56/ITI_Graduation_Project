import Chart from "chart.js/auto";

document.addEventListener("DOMContentLoaded", () => {
    const stats = window.dashboardStats;

    // ===== System Overview (Bar) =====
    new Chart(document.getElementById("overviewChart"), {
        type: "bar",
        data: {
            labels: ["Patients", "Doctors", "Appointments"],
            datasets: [
                {
                    label: "Count",
                    data: [stats.patients, stats.doctors, stats.appointments],
                    backgroundColor: ["#2563eb", "#16a34a", "#dc2626"],
                },
            ],
        },
    });

    // ===== Appointments Last 7 Days (Line) =====
    const days = window.last7Days.map((d) => d.date);
    const counts = window.last7Days.map((d) => d.count);

    new Chart(document.getElementById("appointments7Days"), {
        type: "line",
        data: {
            labels: days,
            datasets: [
                {
                    label: "Appointments",
                    data: counts,
                    borderColor: "#16a34a",
                    backgroundColor: "rgba(22,163,52,0.2)",
                    tension: 0.4,
                },
            ],
        },
    });

    // ===== Appointments Status (Pie) =====
    new Chart(document.getElementById("statusChart"), {
        type: "pie",
        data: {
            labels: ["Pending", "Confirmed", "Cancelled", "Completed"],
            datasets: [
                {
                    data: [
                        stats.pending,
                        stats.confirmed,
                        stats.cancelled,
                        stats.completed,
                    ],
                    backgroundColor: [
                        "#facc15",
                        "#16a34a",
                        "#dc2626",
                        "#2563eb",
                    ],
                },
            ],
        },
    });

    // ===== Top Specialties (Bar) =====
    const specialtyNames = window.topSpecialties.map((s) => s.name);
    const specialtyCounts = window.topSpecialties.map(
        (s) => s.doctors_count || 0
    );

    new Chart(document.getElementById("specialtiesChart"), {
        type: "bar",
        data: {
            labels: specialtyNames,
            datasets: [
                {
                    label: "Doctors",
                    data: specialtyCounts,
                    backgroundColor: "#7c3aed",
                },
            ],
        },
    });

    // ===== Revenue Per Day (Line) =====
    const revenueDays = window.revenuePerDay.map((d) => d.date);
    const revenueValues = window.revenuePerDay.map((d) => d.amount);

    new Chart(document.getElementById("revenueChart"), {
        type: "line",
        data: {
            labels: revenueDays,
            datasets: [
                {
                    label: "Revenue ($)",
                    data: revenueValues,
                    borderColor: "#2563eb",
                    backgroundColor: "rgba(37,99,235,0.2)",
                    tension: 0.4,
                },
            ],
        },
    });
});
