<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Unloading Monitoring System | Minimalist White & Yellow</title>
    <link rel="stylesheet" href="./styles/index.css">
</head>

<body>
    <div class="dashboard">
        <div class="hero">
            <h1>
                UNLOADING + ISM SYSTEM
            </h1>
            <div class="subhead">
                Generate ISM, generate unloading monitoring form and track unloading details.
            </div>
        </div>

        <!-- yellow minimal accent separator -->
        <div class="yellow-dot-pattern"></div>

        <div class="nav-grid">
            <a href="unloading_form.php" class="nav-card">
                <div class="card-content">
                    <span class="card-title">Create Unloading</span>
                    <span class="card-desc">Register new unloading entry</span>
                </div>
                <div class="arrow-icon">→</div>
            </a>

            <a href="unloading_list.php" class="nav-card">
                <div class="card-content">
                    <span class="card-title">Unloading List</span>
                    <span class="card-desc">View & manage all unloadings</span>
                </div>
                <div class="arrow-icon">→</div>
            </a>

            <a href="ism_list.php" class="nav-card">
                <div class="card-content">
                    <span class="card-title">ISM List</span>
                    <span class="card-desc">Inventory Stock Movement System</span>
                </div>
                <div class="arrow-icon">→</div>
            </a>
        </div>

        <div class="info-strip">
            <div class="live-date">
                <span class="dot"></span>
                <span id="currentDate"></span>
            </div>
            <div class="badge">
                Stellar Seeds Corp.
            </div>
        </div>
    </div>

    <script>
        (function() {
            const dateElem = document.getElementById('currentDate');
            if (dateElem) {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate = now.toLocaleDateString(undefined, options);
                dateElem.textContent = formattedDate;
            }
        })();
    </script>
</body>

</html>