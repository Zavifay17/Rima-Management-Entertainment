/* -------------------------------------------------------------
 * JavaScript Logic: Rima Entertainment (RME) Event Rental Page
 * Features: Mobile Menu, Scroll Spy, Live Cost Estimator, 
 *           Package Autolink, AJAX Laravel Submit with CSRF token,
 *           Form Validation & Success Receipt Modal
 * ------------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", () => {
    // 1. Initialize Lucide Icons
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    // 2. DOM Elements
    const header = document.getElementById("header");
    const navMenu = document.getElementById("navMenu");
    const mobileToggle = document.getElementById("mobileToggle");
    const menuIcon = document.getElementById("menuIcon");
    const navLinks = document.querySelectorAll(".nav-link");
    const tabBtns = document.querySelectorAll(".tab-btn");
    const catalogItems = document.querySelectorAll(".catalog-item");
    const bookingForm = document.getElementById("bookingForm");
    const packageCheckboxes = document.querySelectorAll('input[name="selectedPackages[]"]');
    const durationInput = document.getElementById("duration");
    const eventDateInput = document.getElementById("eventDate");
    
    // Live Estimator Display Elements
    const estSubtotal = document.getElementById("estSubtotal");
    const estDuration = document.getElementById("estDuration");
    const estTotal = document.getElementById("estTotal");
    
    // Success Modal Elements
    const successModal = document.getElementById("successModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const whatsappDirectBtn = document.getElementById("whatsappDirectBtn");
    const receiptOrderId = document.getElementById("receiptOrderId");
    const receiptName = document.getElementById("receiptName");
    const receiptWhatsapp = document.getElementById("receiptWhatsapp");
    const receiptDate = document.getElementById("receiptDate");
    const receiptDuration = document.getElementById("receiptDuration");
    const receiptPackages = document.getElementById("receiptPackages");
    const receiptTotal = document.getElementById("receiptTotal");

    // 3. Scroll Effect & Active Link Scroll Spy
    window.addEventListener("scroll", () => {
        // Sticky header class
        if (window.scrollY > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }

        // Scroll Spy: highlight active menu link
        let currentSection = "";
        const sections = document.querySelectorAll("section");
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 140;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                currentSection = section.getAttribute("id");
            }
        });

        navLinks.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href") === `#${currentSection}`) {
                link.classList.add("active");
            }
        });
    });

    // 4. Mobile Navigation Toggle
    mobileToggle.addEventListener("click", () => {
        const isOpen = navMenu.classList.toggle("open");
        
        // Update menu icon (menu -> x)
        if (isOpen) {
            menuIcon.setAttribute("data-lucide", "x");
        } else {
            menuIcon.setAttribute("data-lucide", "menu");
        }
        
        if (typeof lucide !== "undefined") {
            lucide.createIcons({
                attrs: {
                    id: "menuIcon"
                }
            });
        }
    });

    // Close menu when nav link is clicked (Mobile)
    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            if (navMenu.classList.contains("open")) {
                navMenu.classList.remove("open");
                menuIcon.setAttribute("data-lucide", "menu");
                if (typeof lucide !== "undefined") {
                    lucide.createIcons({
                        attrs: {
                            id: "menuIcon"
                        }
                    });
                }
            }
        });
    });

    // Set minimum date for event to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDateStr = tomorrow.toISOString().split("T")[0];
    eventDateInput.setAttribute("min", minDateStr);

    // 5. Catalog Category Tab Filter
    tabBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            // Remove active from all tabs
            tabBtns.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            const filterValue = btn.getAttribute("data-tab");

            // Filter items with scale animation
            catalogItems.forEach(item => {
                const category = item.getAttribute("data-category");
                if (filterValue === "all" || category === filterValue) {
                    item.style.display = "flex";
                    setTimeout(() => {
                        item.style.opacity = "1";
                        item.style.transform = "scale(1)";
                    }, 50);
                } else {
                    item.style.opacity = "0";
                    item.style.transform = "scale(0.95)";
                    setTimeout(() => {
                        item.style.display = "none";
                    }, 300);
                }
            });
        });
    });

    // 6. Live Cost Estimator Logic
    const calculateEstimatedPrice = () => {
        let subtotal = 0;
        let selectedCount = 0;

        packageCheckboxes.forEach(checkbox => {
            const cardElement = checkbox.closest('.package-checkbox-card');
            if (checkbox.checked) {
                subtotal += parseInt(checkbox.getAttribute("data-price"), 10);
                selectedCount++;
                if (cardElement) cardElement.classList.add("selected-active");
            } else {
                if (cardElement) cardElement.classList.remove("selected-active");
            }
        });

        const duration = parseInt(durationInput.value, 10) || 1;

        // Visual duration text
        estDuration.textContent = `${duration} Hari`;

        // Format currency helper
        const formatRupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                maximumFractionDigits: 0
            }).format(number);
        };

        estSubtotal.textContent = formatRupiah(subtotal);
        
        // Multiplier duration discount logic (optional premium feel):
        // 1 day = 100%, 2 days = 10% discount on second day onwards etc.
        let discount = 1.0;
        if (duration >= 3 && duration < 7) {
            discount = 0.95; // 5% discount for 3+ days
        } else if (duration >= 7) {
            discount = 0.90; // 10% discount for 7+ days
        }
        
        const total = Math.round((subtotal * duration) * discount);
        estTotal.textContent = formatRupiah(total);

        // Save selected values to local data for modal receipt later
        return {
            subtotal,
            duration,
            total,
            selectedCount
        };
    };

    // Listeners for calculator updates (Category-level Mutual Exclusion)
    packageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", () => {
            if (checkbox.checked) {
                const value = checkbox.value; // e.g., "sound-5000w", "light-hemat", "stage-6x5"
                const categoryPrefix = value.split("-")[0]; // "sound", "light", "stage"
                
                // Uncheck other checkboxes in the SAME category only
                packageCheckboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== checkbox) {
                        const otherPrefix = otherCheckbox.value.split("-")[0];
                        if (otherPrefix === categoryPrefix) {
                            otherCheckbox.checked = false;
                        }
                    }
                });
            }
            calculateEstimatedPrice();
            // Remove error if package is selected
            const errorParent = document.getElementById("packagesError").parentElement;
            if (errorParent) errorParent.classList.remove("has-error");
        });
    });

    durationInput.addEventListener("input", calculateEstimatedPrice);
    durationInput.addEventListener("change", calculateEstimatedPrice);

    // Initial calculation
    calculateEstimatedPrice();

    // 7. Direct Package Select Hook from Catalog Card
    window.selectDirectPackage = (pkgId) => {
        // Map of catalog IDs to checkbox IDs
        const checkboxMap = {
            'sound-5000w': 'pkg-sound-5000w',
            'sound-10000w': 'pkg-sound-10000w',
            'sound-20000w': 'pkg-sound-20000w',
            'light-hemat': 'pkg-light-hemat',
            'light-menengah': 'pkg-light-menengah',
            'light-mewah': 'pkg-light-mewah',
            'stage-6x5': 'pkg-stage-6x5',
            'stage-8x6': 'pkg-stage-8x6',
            'stage-10x8': 'pkg-stage-10x8',
            'concert-mega': ['pkg-sound-20000w', 'pkg-light-mewah', 'pkg-stage-10x8'] // Hero combo across different categories!
        };

        const target = checkboxMap[pkgId];
        
        // Reset checkboxes first
        packageCheckboxes.forEach(cb => cb.checked = false);

        if (Array.isArray(target)) {
            target.forEach(id => {
                const cb = document.getElementById(id);
                if (cb) cb.checked = true;
            });
        } else if (target) {
            const cb = document.getElementById(target);
            if (cb) cb.checked = true;
        }

        // Recalculate cost
        calculateEstimatedPrice();

        // Smooth scroll to form section
        const formSection = document.getElementById("rent-form-section");
        if (formSection) {
            formSection.scrollIntoView({ behavior: "smooth", block: "start" });
        }

        // Flash form to draw attention
        const formCard = document.querySelector(".rent-form-card");
        if (formCard) {
            formCard.style.outline = "2px solid var(--color-accent)";
            setTimeout(() => {
                formCard.style.outline = "none";
                formCard.style.transition = "outline 0.5s ease";
            }, 1200);
        }
    };

    // 8. Form Validation Logic (Frontend Pre-Check)
    const validateForm = () => {
        let isValid = true;
        
        const fullName = document.getElementById("fullName");
        const whatsapp = document.getElementById("whatsapp");
        const email = document.getElementById("email");
        const eventDate = document.getElementById("eventDate");
        const duration = document.getElementById("duration");

        // Helper to mark validation error
        const setError = (inputElement, errorId, show, customText = null) => {
            const parent = inputElement.closest(".form-group");
            const errorSpan = document.getElementById(errorId);
            if (customText && errorSpan) {
                errorSpan.textContent = customText;
            }
            if (show) {
                parent.classList.add("has-error");
                isValid = false;
            } else {
                parent.classList.remove("has-error");
            }
        };

        // Full Name Validation
        if (!fullName.value.trim()) {
            setError(fullName, "fullNameError", true, "Nama lengkap atau nama instansi wajib diisi.");
        } else {
            setError(fullName, "fullNameError", false);
        }

        // WhatsApp Validation
        const phoneRegex = /^[0-9+]{8,15}$/;
        const cleanPhone = whatsapp.value.trim().replace(/[-\s]/g, "");
        if (!cleanPhone || !phoneRegex.test(cleanPhone)) {
            setError(whatsapp, "whatsappError", true, "Format nomor WhatsApp tidak valid (8-15 digit angka).");
        } else {
            setError(whatsapp, "whatsappError", false);
        }

        // Email Validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
            setError(email, "emailError", true, "Format alamat email tidak valid.");
        } else {
            setError(email, "emailError", false);
        }

        // Event Date Validation
        if (!eventDate.value) {
            setError(eventDate, "eventDateError", true, "Tanggal acara wajib dipilih.");
        } else {
            const selectedDate = new Date(eventDate.value);
            const today = new Date();
            today.setHours(0,0,0,0);
            if (selectedDate <= today) {
                setError(eventDate, "eventDateError", true, "Tanggal acara harus di masa depan (mulai besok).");
            } else {
                setError(eventDate, "eventDateError", false);
            }
        }

        // Duration Validation
        const durationVal = parseInt(duration.value, 10);
        if (isNaN(durationVal) || durationVal < 1) {
            setError(duration, "durationError", true, "Durasi sewa minimal 1 hari.");
        } else {
            setError(duration, "durationError", false);
        }

        // Selected Packages Checkbox Validation
        const { selectedCount } = calculateEstimatedPrice();
        const packagesWrapper = document.querySelector(".packages-selection");
        
        if (selectedCount === 0) {
            packagesWrapper.closest(".form-group").classList.add("has-error");
            isValid = false;
        } else {
            packagesWrapper.closest(".form-group").classList.remove("has-error");
        }

        return isValid;
    };

    // Clean error indicators on input
    const inputs = bookingForm.querySelectorAll("input, textarea");
    inputs.forEach(input => {
        input.addEventListener("input", () => {
            const parent = input.closest(".form-group");
            if (parent) parent.classList.remove("has-error");
        });
    });

    // 9. Booking Form Submit Handling (AJAX fetch to Laravel backend)
    bookingForm.addEventListener("submit", (e) => {
        e.preventDefault();

        // Run complete frontend validation first
        const isFormValid = validateForm();

        if (!isFormValid) {
            const firstError = document.querySelector(".has-error");
            if (firstError) {
                firstError.scrollIntoView({ behavior: "smooth", block: "center" });
            }
            return;
        }

        // Disable submit button during processing
        const submitBtn = document.getElementById("submitBtn");
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i data-lucide="loader" class="btn-icon animate-spin"></i> Menyimpan Order ke Supabase...`;
        if (typeof lucide !== "undefined") {
            lucide.createIcons({ attrs: { class: "btn-icon animate-spin" } });
        }

        // Gather form data
        const fullName = document.getElementById("fullName").value.trim();
        const whatsapp = document.getElementById("whatsapp").value.trim();
        const email = document.getElementById("email").value.trim();
        const eventDate = document.getElementById("eventDate").value;
        const duration = parseInt(durationInput.value, 10);
        const specialRequests = document.getElementById("specialRequests").value.trim();

        const selectedPackages = [];
        packageCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedPackages.push(checkbox.value);
            }
        });

        // CSRF Token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send POST request to Laravel
        fetch(bookingForm.getAttribute('action'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                fullName,
                whatsapp,
                email,
                eventDate,
                duration,
                selectedPackages,
                specialRequests
            })
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw { status: response.status, data };
                }
                return data;
            });
        })
        .then(res => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }

            if (res.success) {
                const order = res.order;
                const customer = res.customer;
                const details = res.details;

                // Format packages titles for display
                const pkgTitleMap = {
                    'sound-5000w': 'Sound System Paket 5000W',
                    'sound-10000w': 'Sound System Paket 10000W',
                    'sound-20000w': 'Sound System Paket 20000W',
                    'light-hemat': 'Lighting Paket Hemat',
                    'light-menengah': 'Lighting Paket Menengah',
                    'light-mewah': 'Lighting Paket Mewah',
                    'stage-6x5': 'Panggung Modular 6x5m',
                    'stage-8x6': 'Panggung Modular 8x6m',
                    'stage-10x8': 'Panggung Modular 10x8m'
                };

                const displayPkgNames = selectedPackages.map(p => pkgTitleMap[p] || p);

                // Date Formatting
                const formatIndoDate = (dateStr) => {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateStr).toLocaleDateString('id-ID', options);
                };

                const formatRupiah = (number) => {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        maximumFractionDigits: 0
                    }).format(number);
                };

                receiptOrderId.textContent = `ORD-${order.id_order}`;
                receiptName.textContent = customer.nama;
                receiptWhatsapp.textContent = customer.no_hp;
                receiptDate.textContent = `${formatIndoDate(order.tgl_mulai)} s/d ${formatIndoDate(order.tgl_selesai)}`;
                receiptDuration.textContent = `${duration} Hari`;
                receiptPackages.textContent = displayPkgNames.join(", ");
                receiptTotal.textContent = formatRupiah(order.total_harga);

                // WhatsApp message config
                const waAdminNum = "6281234567890";
                
                let waText = `Halo Rima Entertainment! Saya baru saja melakukan pemesanan sewa alat event via Website:\n\n`;
                waText += `*Order ID:* ORD-${order.id_order}\n`;
                waText += `*Nama Pelanggan:* ${customer.nama}\n`;
                waText += `*WhatsApp:* ${customer.no_hp}\n`;
                waText += `*Tanggal Sewa:* ${formatIndoDate(order.tgl_mulai)} s/d ${formatIndoDate(order.tgl_selesai)} (${duration} Hari)\n`;
                waText += `*Paket Pilihan:* ${displayPkgNames.join(", ")}\n`;
                if (specialRequests) {
                    waText += `*Catatan Khusus:* ${specialRequests}\n`;
                }
                waText += `\n*Total Biaya:* ${formatRupiah(order.total_harga)}`;
                
                const encodedWaText = encodeURIComponent(waText);
                const waLink = `https://api.whatsapp.com/send?phone=${waAdminNum}&text=${encodedWaText}`;
                whatsappDirectBtn.setAttribute("href", waLink);

                // Show success modal
                successModal.classList.add("open");
                document.body.style.overflow = "hidden"; // Prevent background scroll
            }
        })
        .catch(err => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }

            console.error("Submission error: ", err);

            if (err.status === 422 && err.data && err.data.errors) {
                // Laravel backend validation failed: display errors in form
                const errors = err.data.errors;
                
                // Helper to mark validation error
                const setError = (inputElement, errorId, errorText) => {
                    const parent = inputElement.closest(".form-group");
                    const errorSpan = document.getElementById(errorId);
                    if (errorSpan) errorSpan.textContent = errorText;
                    if (parent) parent.classList.add("has-error");
                };

                if (errors.fullName) {
                    setError(document.getElementById("fullName"), "fullNameError", errors.fullName[0]);
                }
                if (errors.whatsapp) {
                    setError(document.getElementById("whatsapp"), "whatsappError", errors.whatsapp[0]);
                }
                if (errors.email) {
                    setError(document.getElementById("email"), "emailError", errors.email[0]);
                }
                if (errors.eventDate) {
                    setError(document.getElementById("eventDate"), "eventDateError", errors.eventDate[0]);
                }
                if (errors.duration) {
                    setError(document.getElementById("duration"), "durationError", errors.duration[0]);
                }
                if (errors.selectedPackages) {
                    document.getElementById("packagesError").textContent = errors.selectedPackages[0];
                    document.querySelector(".packages-selection").closest(".form-group").classList.add("has-error");
                }

                // Scroll to first error
                const firstError = document.querySelector(".has-error");
                if (firstError) {
                    firstError.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            } else {
                alert(err.data?.message || "Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.");
            }
        });
    });

    // 10. Close success modal
    const closeModal = () => {
        successModal.classList.remove("open");
        document.body.style.overflow = ""; // Re-enable background scroll
        
        // Reset form completely & recalculate cost
        bookingForm.reset();
        calculateEstimatedPrice();
    };

    closeModalBtn.addEventListener("click", closeModal);
    
    // Close modal when clicking outside modal card (overlay backdrop)
    successModal.addEventListener("click", (e) => {
        if (e.target === successModal) {
            closeModal();
        }
    });
});
