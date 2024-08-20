

<div class="pct-c-btn">
    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
        <i class="ph-duotone ph-gear-six"></i>
    </a>
</div>
<div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Settings</h5>
        <button type="button" class="btn btn-icon btn-link-danger ms-auto" data-bs-dismiss="offcanvas"
            aria-label="Close"><i class="ti ti-x"></i></button>
    </div>
    <div class="pct-body customizer-body">
        <div class="offcanvas-body py-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="pc-dark">
                        <h6 class="mb-1">Theme Mode</h6>
                        <p class="text-muted text-sm">Choose light or dark mode or Auto</p>
                        <div class="row theme-color theme-layout">
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn active" data-value="true"
                                        onclick="layout_change('light');" data-bs-toggle="tooltip" title="Light">
                                        <svg class="pc-icon text-warning">
                                            <use xlink:href="#custom-sun-1"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="false" onclick="layout_change('dark');"
                                        data-bs-toggle="tooltip" title="Dark">
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-moon"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="default"
                                        onclick="layout_change_default();" data-bs-toggle="tooltip"
                                        title="Automatically sets the theme based on user's operating system's color scheme.">
                                        <span class="pc-lay-icon d-flex align-items-center justify-content-center">
                                            <i class="ph-duotone ph-cpu"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Theme Contrast</h6>
                    <p class="text-muted text-sm">Choose theme contrast</p>
                    <div class="row theme-contrast">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="true"
                                    onclick="layout_theme_contrast_change('true');" data-bs-toggle="tooltip"
                                    title="True">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn active" data-value="false"
                                    onclick="layout_theme_contrast_change('false');" data-bs-toggle="tooltip"
                                    title="False">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask-1-outline"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
                <!--<li class="list-group-item">-->
                <!--    <h6 class="mb-1">Custom Theme</h6>-->
                <!--    <p class="text-muted text-sm">Choose your primary theme color</p>-->
                <!--    <div class="theme-color preset-color">-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Blue" data-value="preset-1"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Indigo" data-value="preset-2"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Purple" data-value="preset-3"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Pink" data-value="preset-4"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Red" data-value="preset-5"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Orange" data-value="preset-6"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Yellow" data-value="preset-7"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Green" class="active"-->
                <!--            data-value="preset-8"><i class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Teal" data-value="preset-9"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--        <a href="#!" data-bs-toggle="tooltip" title="Cyan" data-value="preset-10"><i-->
                <!--                class="ti ti-checks"></i></a>-->
                <!--    </div>-->
                <!--</li>-->
            </ul>
        </div>
    </div>
</div>
