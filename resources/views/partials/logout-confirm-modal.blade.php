<div id="logout-confirm-overlay" class="lcm-overlay" role="dialog" aria-modal="true" aria-labelledby="lcm-title">
    <div class="lcm-box">
        <div class="lcm-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <h3 id="lcm-title" class="lcm-title">Konfirmasi Logout</h3>
        <p class="lcm-text">Apakah kamu yakin ingin keluar dari akun ini?</p>
        <div class="lcm-actions">
            <button type="button" class="lcm-btn lcm-btn--cancel" onclick="lcmCancelLogout()">
                Batal
            </button>
            <button type="button" class="lcm-btn lcm-btn--confirm" onclick="lcmConfirmLogout()">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<style>
    .lcm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .lcm-overlay.lcm-overlay--open {
        display: flex;
    }

    .lcm-box {
        background: #ffffff;
        border-radius: 16px;
        max-width: 360px;
        width: 100%;
        padding: 28px 24px 20px;
        text-align: center;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        animation: lcm-pop 0.15s ease-out;
    }

    @keyframes lcm-pop {
        from { transform: scale(0.94); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    .lcm-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #fef2f2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }

    .lcm-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .lcm-text {
        font-size: 14px;
        color: #64748b;
        margin: 0 0 20px;
    }

    .lcm-actions {
        display: flex;
        gap: 10px;
    }

    .lcm-btn {
        flex: 1;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: 1.5px solid transparent;
        transition: opacity 0.15s ease;
    }

    .lcm-btn:hover {
        opacity: 0.85;
    }

    .lcm-btn--cancel {
        background: #f1f5f9;
        color: #334155;
        border-color: #e2e8f0;
    }

    .lcm-btn--confirm {
        background: #ef4444;
        color: #ffffff;
    }
</style>

<script>
    let lcmTargetFormId = null;

    function confirmLogoutForm(formId) {
        lcmTargetFormId = formId;
        document.getElementById('logout-confirm-overlay').classList.add('lcm-overlay--open');
    }

    function lcmCancelLogout() {
        document.getElementById('logout-confirm-overlay').classList.remove('lcm-overlay--open');
        lcmTargetFormId = null;
    }

    function lcmConfirmLogout() {
        if (lcmTargetFormId) {
            const form = document.getElementById(lcmTargetFormId);
            if (form) {
                form.submit();
                return;
            }
        }
        lcmCancelLogout();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('logout-confirm-overlay');
        if (!overlay) return;

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                lcmCancelLogout();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('lcm-overlay--open')) {
                lcmCancelLogout();
            }
        });
    });
</script>