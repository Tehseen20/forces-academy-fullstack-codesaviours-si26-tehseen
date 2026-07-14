<?php
$currentPage = basename($_SERVER['PHP_SELF']);
function fa_nav_active($page, $current) {
    return $page === $current ? 'active' : '';
}
?>
<aside class="fa-sidebar">
    <div class="fa-sidebar-brand">
        <div class="fa-sidebar-emblem">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L20 5.5V11C20 16 16.5 19.7 12 21C7.5 19.7 4 16 4 11V5.5L12 2Z" stroke="#C6A15B" stroke-width="1.4" stroke-linejoin="round"/>
                <path d="M9 11.5L11 13.5L15.5 9" stroke="#C6A15B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="fa-sidebar-brand-text">
            Forces Academy
            <small>Student Portal</small>
        </div>
    </div>

    <nav class="fa-sidebar-nav">
        <a href="dashboard.php" class="<?php echo fa_nav_active('dashboard.php', $currentPage); ?>">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="4" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/></svg>
            Dashboard
        </a>
        <a href="courses.php" class="<?php echo fa_nav_active('courses.php', $currentPage); ?>">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5C4 4.67 4.67 4 5.5 4H12V20H5.5C4.67 20 4 19.33 4 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M20 5.5C20 4.67 19.33 4 18.5 4H12V20H18.5C19.33 20 20 19.33 20 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
            My Courses
        </a>
        <a href="assignments.php" class="<?php echo fa_nav_active('assignments.php', $currentPage); ?>">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15V6H9V3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M5.5 6H18.5C19.33 6 20 6.67 20 7.5V19.5C20 20.33 19.33 21 18.5 21H5.5C4.67 21 4 20.33 4 19.5V7.5C4 6.67 4.67 6 5.5 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 12L11 15L16 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Assignments
        </a>
        <a href="results.php" class="<?php echo fa_nav_active('results.php', $currentPage); ?>">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 20V13M12 20V8M19 20V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            My Results
        </a>
        <a href="notices.php" class="<?php echo fa_nav_active('notices.php', $currentPage); ?>">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 4.5C5 3.67 5.67 3 6.5 3H14L19 8V19.5C19 20.33 18.33 21 17.5 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3V8H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8.5 13H15.5M8.5 16.5H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Notices
        </a>

        <div class="fa-sidebar-divider"></div>

        <a href="logout.php" class="fa-sidebar-logout">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 4H18.5C19.33 4 20 4.67 20 5.5V18.5C20 19.33 19.33 20 18.5 20H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M4 12H14.5M14.5 12L11 8.5M14.5 12L11 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Logout
        </a>
    </nav>
</aside>
