document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------
    // 🔹 Основные функции
    // -------------------------------
    const getCart = () => JSON.parse(localStorage.getItem('cart')) || []
    const saveCart = (cart) => localStorage.setItem('cart', JSON.stringify(cart))
    const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

    const updateHeaderCount = () => {
        const countEl = document.querySelector('.cart-btn span.cart-count')
        if (!countEl) return
        const cart = getCart()
        const totalCount = cart.reduce((acc, item) => acc + item.quantity, 0)
        countEl.textContent = totalCount > 0 ? totalCount : ''
        countEl.style.display = totalCount > 0 ? 'inline-block' : 'none'
    }

    const generateProductId = (productEl) => {
        const name = productEl.querySelector('.about-product__title, .popular__item-name, .categories__name, .related-products__name')?.textContent?.trim() || ''
        const price = productEl.querySelector('.about-product__price span, .categories__price span, .popular__item-price span, .related-products__price span')?.textContent?.replace(/[^\d]/g, '') || ''
        const key = `${name}-${price}`
        let hash = 0
        for (let i = 0; i < key.length; i++) { hash = (hash << 5) - hash + key.charCodeAt(i); hash |= 0 }
        return `p${Math.abs(hash)}`
    }

    const ensureToastContainer = () => {
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
            c.style.gap = '12px'
            document.body.appendChild(c)
        }
        return c
    }

    const showToast = (msg, type = 'success') => {
        const COLORS = {
            success: '#16a34a', // зелёный
            error:   '#ef4444', // красный
            info:    '#3b82f6', // синий
            warning: '#f59e0b', // жёлтый
            default: '#0ea5e9', // голубой
        }

        const c = ensureToastContainer()
        const t = document.createElement('div')
        t.innerHTML = msg
        t.style.minWidth = '320px'
        t.style.maxWidth = '520px'
        t.style.padding = '16px 18px'
        t.style.borderRadius = '14px'
        t.style.boxShadow = '0 15px 40px rgba(0,0,0,.18)'
        t.style.fontSize = '16px'
        t.style.lineHeight = '1.35'
        t.style.fontWeight = '600'
        t.style.color = '#fff'
        t.style.background = 'rgba(17,24,39,.96)' // тёмный фон
        t.style.border = `2px solid ${COLORS[type] || COLORS.default}` // 🔷 цветной бордер

        t.style.opacity = '0'
        t.style.transform = 'translateY(6px)' // снизу — поднимаем
        t.style.transition = 'opacity .25s ease, transform .25s ease'
        c.appendChild(t)

        requestAnimationFrame(() => {
            t.style.opacity = '1'
            t.style.transform = 'translateY(0)'
        })

        setTimeout(() => {
            t.style.opacity = '0'
            t.style.transform = 'translateY(6px)'
            setTimeout(() => t.remove(), 250)
        }, 3500)
    }


    // -------------------------------
    // 🔹 Добавить товар в корзину
    // -------------------------------
    const addToCart = (product) => {
        const cart = getCart()
        const existing = cart.find(item => item.id === product.id)
        if (existing) {
            existing.quantity += product.quantity
        } else {
            cart.push(product)
        }
        saveCart(cart)
        updateHeaderCount()
    }

    // -------------------------------
    // 🔹 Удалить товар / изменить количество
    // -------------------------------
    const removeFromCart = (id) => {
        const cart = getCart().filter(item => item.id !== id)
        saveCart(cart)
        updateHeaderCount()
        renderCartItems()
    }
    const changeQuantity = (id, delta) => {
        const cart = getCart().map(item => {
            if (item.id === id) {
                const newQty = Math.max(1, item.quantity + delta)
                return { ...item, quantity: newQty }
            }
            return item
        })
        saveCart(cart)
        updateHeaderCount()
        renderCartItems()
    }

    // -------------------------------
    // 🔹 Рендер корзины
    // -------------------------------
    const renderCartItems = () => {
        const cartList = document.querySelector('.cart__list')
        if (!cartList) return

        const cart = getCart()
        cartList.innerHTML = ''

        if (cart.length === 0) {
            cartList.innerHTML = `<p class="cart-empty">${T('cart.empty','Ваша корзина пуста')}</p>`
            return
        }

        cart.forEach(item => {
            const totalPrice = (item.price * item.quantity).toLocaleString('ru-RU').replace(/\s/g, ' ')
            const li = document.createElement('li')
            li.className = 'cart__item'
            li.innerHTML = `
        <a href="#!" class="cart__link">
          <div class="cart__image"><img src="${item.image}" alt="${item.name}"></div>
          <div class="cart__info">
            <h3 class="cart__name">${item.name}</h3>
            <h4 class="cart__code">${T('cart.code','Код товара')}: ${item.id}</h4>
          </div>
          <div class="cart__price">${totalPrice} T</div>
        </a>
        <div class="cart__control">
          <div class="cart-btns">
            <button class="cart-button cart-button-minus" ${item.quantity === 1 ? 'disabled' : ''}>-</button>
            <span class="cart-count">${item.quantity}</span>
            <button class="cart-button cart-button-plus">+</button>
          </div>
          <button class="delete" title="Удалить товар">
                      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 5H4.16667H17.5" stroke="#01714B" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M15.8337 5.00002V16.6667C15.8337 17.1087 15.6581 17.5326 15.3455 17.8452C15.0329 18.1578 14.609 18.3334 14.167 18.3334H5.83366C5.39163 18.3334 4.96771 18.1578 4.65515 17.8452C4.34259 17.5326 4.16699 17.1087 4.16699 16.6667V5.00002M6.66699 5.00002V3.33335C6.66699 2.89133 6.84259 2.4674 7.15515 2.15484C7.46771 1.84228 7.89163 1.66669 8.33366 1.66669H11.667C12.109 1.66669 12.5329 1.84228 12.8455 2.15484C13.1581 2.4674 13.3337 2.89133 13.3337 3.33335V5.00002"
                          stroke="#01714B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8.33301 9.16669V14.1667" stroke="#01714B" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path d="M11.667 9.16669V14.1667" stroke="#01714B" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round" />
                      </svg>          </button>
        </div>
      `
            cartList.appendChild(li)
            li.querySelector('.cart-button-minus')?.addEventListener('click', () => changeQuantity(item.id, -1))
            li.querySelector('.cart-button-plus')?.addEventListener('click', () => changeQuantity(item.id, +1))
            li.querySelector('.delete')?.addEventListener('click', () => removeFromCart(item.id))
        })
    }

    // -------------------------------
    // 🔹 Кнопки "Добавить в корзину" — берём ИД из data-product ✨
    // -------------------------------
    const addButtons = document.querySelectorAll('.add-to-cart')
    addButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            const productEl = btn.closest('.popular__item, .categories__item, .related-products__item, .about-product')
            if (!productEl) return

            // сначала пробуем data-product (из Blade)
            let payload = null
            const raw = btn.getAttribute('data-product')
            if (raw) { try { payload = JSON.parse(raw) } catch (_) {} }

            let id, name, price
            if (payload && payload.id) {
                id = String(payload.id)                // реальный product.id
                name = payload.name || 'Товар'
                price = parseInt(String(payload.price || '0').replace(/[^\d]/g, '')) || 0
            } else {
                // фоллбэк
                id = productEl.querySelector('.about-product__code, .categories__code, .cart__code')?.textContent?.replace(/\D/g, '') ||
                    generateProductId(productEl)
                const titleEl = productEl.querySelector('.about-product__title, .popular__item-name, .categories__name, .related-products__name')
                name = titleEl?.childNodes[0]?.textContent?.trim() || titleEl?.textContent.trim() || 'Товар'
                const priceElement =
                    productEl.querySelector('.about-product__price span') ||
                    productEl.querySelector('.categories__price span') ||
                    productEl.querySelector('.popular__item-price span') ||
                    productEl.querySelector('.related-products__price span')
                price = parseInt(priceElement?.textContent.trim().replace(/[^\d]/g, '') || 0)
            }

            let image
            if (productEl.classList.contains('about-product')) {
                image = productEl.querySelector('.about-product-swiper .swiper-slide-active img')?.getAttribute('src') ||
                    productEl.querySelector('.about-product-swiper img')?.getAttribute('src')
            } else {
                image = productEl.querySelector('img')?.getAttribute('src')
            }
            if (!image) image = 'assets/img/no-image.png'

            const qtyEl = productEl.querySelector('.cart-count')
            const quantity = qtyEl ? parseInt(qtyEl.textContent) || 1 : 1

            addToCart({ id, name, price, image, quantity })

            btn.classList.add('added', 'in-cart')
            btn.textContent = T('cart.in_cart', 'В корзине')
            btn.disabled = true
            showToast(T('toast.added', 'Товар добавлен в корзину'))
        })
    })

    // -------------------------------
    // 🔹 Счетчик на странице товара
    // -------------------------------
    const aboutProduct = document.querySelector('.about-product')
    if (aboutProduct) {
        const countEl = aboutProduct.querySelector('.cart-count')
        const plusBtn = aboutProduct.querySelector('.cart-button-plus')
        const minusBtn = aboutProduct.querySelector('.cart-button-minus')
        if (countEl && plusBtn && minusBtn) {
            let count = parseInt(countEl.textContent)
            plusBtn.addEventListener('click', () => { count++; countEl.textContent = count })
            minusBtn.addEventListener('click', () => { if (count > 1) { count--; countEl.textContent = count } })
        }
    }

    // -------------------------------
    // 🔹 Отправка заказа на сервер ✨
    // -------------------------------
    const orderForm = document.getElementById('orderForm')
    if (orderForm) {
        orderForm.addEventListener('submit', async (e) => {
            e.preventDefault()

            const cart = getCart()
            if (!cart.length) {
                showToast(T('toast.cart_empty','Корзина пуста'), 'error')
                return
            }

            // берём id и quantity; id должны быть числом (product_id из БД)
            const items = cart.map(i => ({
                id: parseInt(String(i.id).replace(/[^\d]/g, '')),
                quantity: i.quantity
            })).filter(x => x.id > 0)

            if (!items.length) {
                showToast(T('toast.parse_error','Не удалось распознать товары'), 'error')
                return
            }

            const payload = {
                name:    orderForm.querySelector('#name')?.value?.trim(),
                phone:   orderForm.querySelector('#tel')?.value?.trim(),
                address: orderForm.querySelector('#address')?.value?.trim(),
                comment: orderForm.querySelector('#comment')?.value?.trim(),
                items
            }

            // простая валидация
            if (!payload.name || !payload.phone || !payload.address) {
                showToast(T('toast.fill_required','Заполните имя, телефон и адрес'), 'error')
                return
            }

            try {
                const res = await fetch('/cart/order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrf(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                })

                const data = await res.json().catch(() => ({}))
                if (!res.ok || !data.ok) {
                    console.error('Order error:', data)
                    showToast(data.message || T('toast.order_error','Ошибка оформления заказа'), 'error')
                    return
                }

                showToast(T('toast.order_ok','✅ Заказ принят! № :number').replace(':number', data.number), 'success')
                localStorage.removeItem('cart')
                renderCartItems()
                updateHeaderCount()
                orderForm.reset()
            } catch (err) {
                console.error(err)
                showToast('Ошибка сети', 'error')
            }
        })
    }

    // -------------------------------
    // 🔹 Инициализация
    // -------------------------------
    updateHeaderCount()

    // проверка кнопки "в корзине" на странице товара
    const checkProductInCart = () => {
        const cart = getCart()
        const productSection = document.querySelector('.about-product')
        if (!productSection) return
        const id = productSection.querySelector('.about-product__code')?.textContent?.replace(/\D/g, '') || productSection.dataset.id
        if (!id) return
        const isInCart = cart.some(item => String(item.id) === String(id))
        if (isInCart) {
            const btn = productSection.querySelector('.add-to-cart')
            if (btn) { btn.classList.add('added'); btn.textContent = 'В корзине'; btn.disabled = true }
        }
    }
    checkProductInCart()

    // синхронизируем все кнопки
    const syncAddToCartButtons = () => {
        const cart = getCart()
        const addButtons = document.querySelectorAll('.add-to-cart')
        addButtons.forEach(btn => {
            const productEl = btn.closest('.popular__item, .categories__item, .related-products__item, .about-product')
            if (!productEl) return
            let id
            const raw = btn.getAttribute('data-product')
            if (raw) {
                try { const p = JSON.parse(raw); id = p?.id } catch (_) {}
            }
            if (!id) {
                id = productEl.querySelector('.about-product__code, .categories__code, .cart__code')?.textContent?.replace(/\D/g, '') ||
                    generateProductId(productEl)
            }
            const isInCart = cart.some(item => String(item.id) === String(id))
            if (isInCart) { btn.classList.add('added'); btn.textContent = T('cart.in_cart','В корзине'); btn.disabled = true }
            else { btn.classList.remove('added'); btn.textContent = T('cart.add','Добавить в корзину'); btn.disabled = false }
        })
    }
    syncAddToCartButtons()
    renderCartItems()

    const tel = document.querySelector('#tel');
    if (window.Inputmask && tel) {
        const im = new Inputmask('+7 (999) 999 99 99', {
            showMaskOnHover: false,
            clearIncomplete: true,
            placeholder: '_',
        });
        im.mask(tel);
    }
})
