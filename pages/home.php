<div class="home main">
    <h2>Solidarity</h2>
    <div class="content">
        <div class="text">
            <h1>Equity in Action, Unity in Progress</h1>
            <svg width="507" height="34" viewBox="0 0 507 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 30C76.5163 10.3798 277.809 -17.0885 502.85 30" stroke="#7E3223" stroke-width="8"
                    stroke-linecap="round" />
            </svg>
            <p>Solidarity Scale is a platform dedicated to promoting fairness, unity, and collaboration. By providing
                tools and resources to measure and foster equitable practices, we empower individuals, communities, and
                organizations to work together for a more inclusive and sustainable future. Our mission is to turn
                solidarity into actionable progress, creating a balanced foundation for growth and harmony.</p>
            <?php if ($showLogin): ?>
                <a href="./login.php">
                    <div class="start-survey-button">
                        Start Survey
                    </div>

                <?php else: ?>
                    <a href="?page=Survey">
                        <div class="start-survey-button">
                            Start Survey
                        </div>
                    </a>
                </a>
            <?php endif; ?>

            <img id="design1" src="./icons/design1.png" alt="">
        </div>
    </div>
</div>