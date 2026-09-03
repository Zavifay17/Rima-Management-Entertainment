
        // =====================================================
        // 1. PREMIUM PRELOADER
        // =====================================================
        const preloader = document.getElementById('rme-preloader');
        if (preloader) {
            // Apply dark mode to preloader immediately from localStorage
            const savedTheme = localStorage.getItem('rme_theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }

            window.addEventListener('load', () => {
                setTimeout(() => {
                    preloader.classList.add('hide');
                    // Remove from DOM after fade out
                    setTimeout(() => { preloader.remove(); }, 700);
                }, 1200);
            });
            // Fallback: remove after 3.5s even if load hasn't fired
            setTimeout(() => {
                if (preloader && !preloader.classList.contains('hide')) {
                    preloader.classList.add('hide');
                    setTimeout(() => { preloader.remove(); }, 700);
                }
            }, 3500);
        }

        // =====================================================
        // 2. CUSTOM CURSOR (Desktop only)
        // =====================================================
        const cursorDot  = document.getElementById('cursor-dot');
        const cursorRing = document.getElementById('cursor-ring');

        if (cursorDot && cursorRing && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            let dotX = 0, dotY = 0, ringX = 0, ringY = 0;
            let rafId;

            const hoverTargets = 'a, button, .nav-link, .catalog-item, .pop-card, .category-card, .tab-btn, .step-card, .testimonial-card, input, textarea, select, label';

            // Hide default cursor
            document.documentElement.style.cursor = 'none';
            let isCursorVisible = false;

            document.addEventListener('mousemove', e => {
                if (!isCursorVisible) {
                    cursorDot.style.opacity = '1';
                    cursorRing.style.opacity = '1';
                    isCursorVisible = true;
                }
                dotX = e.clientX;
                dotY = e.clientY;

                // Dot follows instantly via JS
                cursorDot.style.left  = dotX + 'px';
                cursorDot.style.top   = dotY  + 'px';
            });

            const ambientBlob = document.getElementById('ambient-blob');

            // Ring uses smooth lerp
            function animateRing() {
                ringX += (dotX - ringX) * 0.12;
                ringY += (dotY - ringY) * 0.12;
                if(cursorRing) {
                    cursorRing.style.left = ringX + 'px';
                    cursorRing.style.top  = ringY + 'px';
                }
                rafId = requestAnimationFrame(animateRing);
            }
            animateRing();

            // Hover magnify effect
            document.querySelectorAll(hoverTargets).forEach(el => {
                el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
                el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
            });

            // Magnetic Elements Logic
            const magneticElements = document.querySelectorAll('.btn, .btn-primary, .btn-outline, .magnetic-btn, .nav-link, .floating-whatsapp, .back-to-top');
            magneticElements.forEach(el => {
                el.addEventListener('mousemove', (e) => {
                    const rect = el.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    // Move the button slightly towards the cursor with immediate response
                    el.style.transition = 'none';
                    el.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
                });
                el.addEventListener('mouseleave', () => {
                    // Spring back with elastic cubic-bezier
                    el.style.transition = 'transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    el.style.transform = `translate(0px, 0px)`;
                });
            });

            // Hide cursor when leaving window
            document.addEventListener('mouseleave', () => {
                cursorDot.style.opacity  = '0';
                cursorRing.style.opacity = '0';
            });
            document.addEventListener('mouseenter', () => {
                cursorDot.style.opacity  = '1';
                cursorRing.style.opacity = '1';
            });
            // =====================================================
        // 3. AMBIENT FLOATING PARTICLES - ADVANCED CONSTELLATION
        // =====================================================
        const canvas = document.getElementById('particle-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let particles = [];
            let W, H;
            let mouse = { x: -9999, y: -9999, radius: 150 };
            const isDesktop = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            if (isDesktop) {
                window.addEventListener('mousemove', (e) => {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;
                    
                    // Spotlight Overlay Logic
                    const spotlight = document.getElementById('spotlight-overlay');
                    if(spotlight) {
                        spotlight.style.opacity = '1';
                        const isDark = document.body.classList.contains('dark-mode');
                        const color = isDark ? 'rgba(99, 102, 241, 0.08)' : 'rgba(255, 255, 255, 0.4)';
                        spotlight.style.background = `radial-gradient(circle 600px at ${e.clientX}px ${e.clientY}px, ${color}, transparent 80%)`;
                    }
                });
                window.addEventListener('mouseleave', () => {
                    mouse.x = -9999; mouse.y = -9999;
                    const spotlight = document.getElementById('spotlight-overlay');
                    if(spotlight) spotlight.style.opacity = '0';
                });
            }

            function resizeCanvas() {
                W = canvas.width  = window.innerWidth;
                H = canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            const PARTICLE_COUNT = window.innerWidth < 768 ? 40 : 80;

            function getParticleColor() {
                const isDark = document.body.classList.contains('dark-mode');
                if (isDark) {
                    const colors = ['rgba(56,189,248,', 'rgba(99,102,241,', 'rgba(167,139,250,'];
                    return colors[Math.floor(Math.random() * colors.length)];
                } else {
                    const colors = ['rgba(10,37,64,', 'rgba(37,99,235,', 'rgba(99,102,241,'];
                    return colors[Math.floor(Math.random() * colors.length)];
                }
            }

            class Particle {
                constructor() {
                    this.x = Math.random() * W;
                    this.y = Math.random() * H;
                    this.baseX = this.x;
                    this.baseY = this.y;
                    this.size = (Math.random() * 2) + 0.5;
                    this.baseSize = this.size;
                    this.density = (Math.random() * 30) + 1;
                    this.color = getParticleColor();
                    this.alpha = Math.random() * 0.5 + 0.2;
                    this.vx = (Math.random() - 0.5) * 0.5;
                    this.vy = (Math.random() - 0.5) * 0.5;
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = this.color + this.alpha + ')';
                    ctx.fill();
                    
                    // Glow
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size * 3, 0, Math.PI * 2);
                    ctx.fillStyle = this.color + (this.alpha * 0.2) + ')';
                    ctx.fill();
                }
                update() {
                    // Drift
                    this.x += this.vx;
                    this.y += this.vy;
                    
                    if (this.x < 0 || this.x > W) this.vx = -this.vx;
                    if (this.y < 0 || this.y > H) this.vy = -this.vy;

                    // Mouse Interaction (Spring Physics)
                    if (isDesktop) {
                        let dx = mouse.x - this.x;
                        let dy = mouse.y - this.y;
                        let distance = Math.sqrt(dx * dx + dy * dy);
                        let forceDirectionX = dx / distance;
                        let forceDirectionY = dy / distance;
                        let maxDistance = mouse.radius;
                        let force = (maxDistance - distance) / maxDistance;
                        let directionX = forceDirectionX * force * this.density;
                        let directionY = forceDirectionY * force * this.density;

                        if (distance < mouse.radius) {
                            this.x -= directionX;
                            this.y -= directionY;
                            this.size = this.baseSize * 2;
                        } else {
                            if (this.x !== this.baseX) {
                                let dx = this.x - this.baseX;
                                this.x -= dx/10;
                            }
                            if (this.y !== this.baseY) {
                                let dy = this.y - this.baseY;
                                this.y -= dy/10;
                            }
                            this.size = this.baseSize;
                        }
                    }
                    
                    // Mobile Touch Burst
                    if (!isDesktop && mouse.x !== -9999) {
                        let dx = mouse.x - this.x;
                        let dy = mouse.y - this.y;
                        let distance = Math.sqrt(dx * dx + dy * dy);
                        if(distance < 100) {
                            this.vx = -dx * 0.05;
                            this.vy = -dy * 0.05;
                        }
                    }
                    
                    this.baseX += this.vx;
                    this.baseY += this.vy;
                }
            }

            for (let i = 0; i < PARTICLE_COUNT; i++) {
                particles.push(new Particle());
            }

            // Mobile Touch Interaction
            if (!isDesktop) {
                window.addEventListener('touchstart', (e) => {
                    mouse.x = e.touches[0].clientX;
                    mouse.y = e.touches[0].clientY;
                    setTimeout(() => { mouse.x = -9999; mouse.y = -9999; }, 200);
                });
            }

            function connect() {
                const isDark = document.body.classList.contains('dark-mode');
                const lineColor = isDark ? '255, 255, 255' : '10, 37, 64';
                let opacityValue = 1;
                for (let a = 0; a < particles.length; a++) {
                    for (let b = a; b < particles.length; b++) {
                        let distance = ((particles[a].x - particles[b].x) * (particles[a].x - particles[b].x))
                                     + ((particles[a].y - particles[b].y) * (particles[a].y - particles[b].y));
                        if (distance < (W/10) * (H/10)) {
                            opacityValue = 1 - (distance / 15000);
                            ctx.strokeStyle = `rgba(${lineColor}, ${opacityValue * 0.2})`;
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            ctx.moveTo(particles[a].x, particles[a].y);
                            ctx.lineTo(particles[b].x, particles[b].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            function animate() {
                requestAnimationFrame(animate);
                ctx.clearRect(0, 0, W, H);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].draw();
                    particles[i].update();
                }
                connect();
            }
            animate();

            // Theme toggle listener
            const themeToggleBtn = document.getElementById('themeToggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    setTimeout(() => {
                        particles.forEach(p => p.color = getParticleColor());
                    }, 50);
                });
            }
        }

        // =====================================================
        // 4. 3D Tilt Effect for Cards
        // ==============================================================
        if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            const tiltCards = document.querySelectorAll('.catalog-item, .pop-card, .category-card');
            tiltCards.forEach(card => {
                card.classList.add('tilt-card');
                
                card.addEventListener('mousemove', e => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    // Set CSS variables for realtime gradient spotlight
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = ((y - centerY) / centerY) * -4; // Softer max rotation 4deg
                    const rotateY = ((x - centerX) / centerX) * 4;
                    
                    const isHighlighted = card.classList.contains('highlighted');
                    const scale = isHighlighted ? 1.06 : 1.01;
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(${scale}, ${scale}, ${scale})`;
                });
                
                card.addEventListener('mouseleave', () => {
                    const isHighlighted = card.classList.contains('highlighted');
                    const scale = isHighlighted ? 1.05 : 1;
                    
                    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(${scale}, ${scale}, ${scale})`;
                    card.style.transition = 'transform 0.5s ease';
                });
                
                card.addEventListener('mouseenter', () => {
                    card.style.transition = 'none'; // Remove transition for smooth tracking
                });
            });
        }

        // =====================================================
        // 5. Scroll Progress & Back-to-Top (Phase 4)
        // =====================================================
        const scrollBar = document.getElementById('scrollBar');
        const backToTopBtn = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            // Scroll Progress
            const scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollPercentage = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
            
            if(scrollBar) {
                scrollBar.style.width = scrollPercentage + '%';
            }
