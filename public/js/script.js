if (typeof window.showToast !== 'function') {
    window.showToast = (msg, type = 'success') => {
        const COLORS = { success:'#16a34a', error:'#ef4444', info:'#3b82f6', warning:'#f59e0b', default:'#0ea5e9' }
        let c = document.getElementById('toast-container')
        if (!c) {
            c = document.createElement('div')
            c.id = 'toast-container'
            c.style.position = 'fixed'
            c.style.right = '20px'
            c.style.bottom = '20px'
            c.style.zIndex = '99999'
            c.style.display = 'flex'
            c.style.flexDirection = 'column'
            c.style.gap = '10px'
            document.body.appendChild(c)
        }
        const t = document.createElement('div')
        t.textContent = msg
        t.style.minWidth = '320px'
        t.style.maxWidth = '520px'
        t.style.padding = '14px 16px'
        t.style.borderRadius = '12px'
        t.style.boxShadow = '0 10px 30px rgba(0,0,0,.18)'
        t.style.fontSize = '15px'
        t.style.lineHeight = '1.35'
        t.style.fontWeight = '600'
        t.style.color = '#fff'
        t.style.background = 'rgba(17,24,39,.96)'
        t.style.border = `2px solid ${COLORS[type] || COLORS.default}`
        t.style.opacity = '0'
        t.style.transform = 'translateY(6px)'
        t.style.transition = 'opacity .2s ease, transform .2s ease'
        c.appendChild(t)
        requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateY(0)' })
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(6px)'; setTimeout(() => t.remove(), 200) }, 3500)
    }
}
// === Burger Menu ===
const burger = document.querySelector('.burger')
const nav = document.querySelector('.header__center')

if (burger && nav) {
  burger.addEventListener('click', () => {
    burger.classList.toggle('active')
    nav.classList.toggle('active')
    document.body.classList.toggle('no-scroll')
  })
}

// === Hero Slider ===
const heroSliderEl = document.querySelector('.hero__slider')

if (heroSliderEl && typeof Swiper !== 'undefined') {
  const heroSwiper = new Swiper(heroSliderEl, {
    loop: true,
    speed: 700,
    autoplay: {
      delay: 555000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    effect: 'fade',
  })
}

// === Popular Slider ===
const popularSliderEl = document.querySelector('.popular__slider')

if (popularSliderEl) {
  const popularSwiper = new Swiper(popularSliderEl, {
    spaceBetween: 15,
    slidesPerView: 1.2,
    centeredSlides: true,
    initialSlide: 1,
    navigation: {
      nextEl: '.popular__btn-next',
      prevEl: '.popular__btn-prev',
    },
    breakpoints: {
      620: {
        slidesPerView: 1.8,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2.3,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3.2,
        spaceBetween: 30,
      },
      1280: {
        slidesPerView: 4.2,
        spaceBetween: 30,
      },
    }

  })
}

// === Banner Slider ===
const bannerSliderEl = document.querySelector('.banner-slider')

if (bannerSliderEl) {
  const bannerSwiper = new Swiper(bannerSliderEl, {
    loop: true,
    speed: 700,
    spaceBetween: 10,
    slidesPerView: 1,
    initialSlide: 1,
    autoplay: {
      delay: 555000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  })
}
// === Brands Slider ===
const brandsSliderEl = document.querySelector('.brands-slider')

if (brandsSliderEl) {
  const brandsSwiper = new Swiper(brandsSliderEl, {
    loop: false,
    speed: 5000,
    spaceBetween: 20,
    slidesPerView: 2.2,
    allowTouchMove: false,
    autoplay: {
      delay: 0,
    },
    breakpoints: {
      767: {
        slidesPerView: 5.5,
        spaceBetween: 40,
      },
      992: {
        slidesPerView: 8,
        spaceBetween: 40,
      },
    }
  })
}


// === Main Revires Slider ===
const mainReviewSliderEl = document.querySelector('.main-reviews-slider')

if (mainReviewSliderEl) {
  const popularSwiper = new Swiper(mainReviewSliderEl, {
    loop: true,
    speed: 700,
    spaceBetween: 10,
    slidesPerView: 1.3,
    initialSlide: 1,
    autoplay: {
      delay: 555000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.main-reviews__btn-next',
      prevEl: '.main-reviews__btn-prev',
    },
    breakpoints: {
      620: {
        slidesPerView: 1.8,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2.5,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3.1,
        spaceBetween: 30,
      },
      1280: {
        slidesPerView: 4.1,
        spaceBetween: 30,
      },
    }

  })
}

const aboutProductMainEl = document.querySelector('.about-product-swiper')
const aboutProductThumbsEl = document.querySelector('.about-product-small')

if (aboutProductMainEl && aboutProductThumbsEl) {
  // Маленький слайдер (превью)
  const aboutProductThumbs = new Swiper(aboutProductThumbsEl, {
    slidesPerView: 3,
    spaceBetween: 21,
    watchSlidesProgress: true,
    slideToClickedSlide: true,
    speed: 500,
  })

  // Большой слайдер (основное изображение)
  const aboutProductMain = new Swiper(aboutProductMainEl, {
    slidesPerView: 1,
    spaceBetween: 10,
    speed: 600,
    loop: true,
    thumbs: {
      swiper: aboutProductThumbs,
    },
  })
}


// === related Slider ===
const relatedSliderEl = document.querySelector('.related-products__swiper')

if (relatedSliderEl) {
  const relatedSwiper = new Swiper(relatedSliderEl, {
    loop: false,
    speed: 75000,
    allowTouchMove: true,
    spaceBetween: 15,
    slidesPerView: 2.1,
    initialSlide: 1,
    autoplay: {
      delay: 10,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.popular__btn-next',
      prevEl: '.popular__btn-prev',
    },
    breakpoints: {
      620: {
        slidesPerView: 2.6,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 3.2,
        spaceBetween: 20,
      },
      1280: {
        slidesPerView: 4.2,
        spaceBetween: 30,
      },
    }

  })

}

const leadPhones = document.querySelectorAll('input[name="phone"]#phone, .form input[type="tel"]#phone');
if (window.Inputmask && leadPhones.length) {
    const im = new Inputmask('+7 (999) 999 99 99', {
        showMaskOnHover: false,
        clearIncomplete: true,
        placeholder: '_',
    });
    leadPhones.forEach((el) => im.mask(el));
}

// === Отправка формы "Контакты" -> /leads ===
(() => {
    const form = document.querySelector('.form')
    if (!form) return

    // стараемся распознать нужную именно "контактную" форму по полям
    const nameEl = form.querySelector('input[name="name"]#name')
    const phoneEl = form.querySelector('input[name="phone"]#phone')
    const msgEl = form.querySelector('textarea[name="message"]#message')

    if (!nameEl || !phoneEl) return

    form.addEventListener('submit', async (e) => {
        e.preventDefault()
        const name = nameEl.value.trim()
        const phone = phoneEl.value.trim()
        const message = (msgEl?.value || '').trim()

        if (!name || !phone) {
            showToast('Заполните имя и телефон', 'error')
            return
        }

        try {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            const res = await fetch('/leads', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name, phone, message })
            })
            const data = await res.json().catch(() => ({}))
            if (!res.ok || !data.ok) {
                console.error('Lead error:', data)
                showToast(T('toast.lead_error','Ошибка отправки заявки'), 'error')
                return
            }
            showToast(T('toast.lead_ok','✅ Заявка отправлена! Менеджер свяжется с вами.'), 'success')
            form.reset()
        } catch (err) {
            console.error(err)
            showToast(T('toast.network','Ошибка сети'), 'error')
        }
    })
})()

