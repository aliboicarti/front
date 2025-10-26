let formData = {};
let currentStep = 1;

function handleFabClick() {
    if (currentStep === 1) {
        nextStep();
    } else if (currentStep === 2) {
        submitForm();
    }
}

function nextStep() {
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (!name || !phone) {
        alert('لطفاً نام و شماره تماس را وارد کنید');
        return;
    }

    formData.name = name;
    formData.phone = phone;
    formData.address = document.getElementById('address').value.trim();
    formData.email = document.getElementById('email').value.trim();

    document.getElementById('step1').classList.remove('active');
    document.getElementById('step2').classList.add('active');
    document.getElementById('fabIcon').textContent = 'send';
    currentStep = 2;
}

async function submitForm() {
    const description = document.getElementById('description').value.trim();

    if (!description) {
        alert('لطفاً توضیحات سفارش را وارد کنید');
        return;
    }

    formData.description = description;

    try {
        // Use the current page URL
        const response = await fetch('?endpoint=orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();
        
        if (response.ok) {
            document.getElementById('step2').classList.remove('active');
            document.getElementById('successMessage').classList.add('active');
            document.getElementById('fabButton').style.display = 'none';

            setTimeout(() => {
                resetForm();
            }, 9000);
        } else {
            alert('خطا در ثبت سفارش: ' + (result.error || 'خطای ناشناخته'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('خطا در ارسال اطلاعات');
    }
}


function resetForm() {
    document.getElementById('successMessage').classList.remove('active');
    document.getElementById('step1').classList.add('active');
    document.getElementById('fabButton').style.display = 'flex';
    document.getElementById('fabIcon').textContent = 'arrow_back';
    
    document.getElementById('name').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('address').value = '';
    document.getElementById('email').value = '';
    document.getElementById('description').value = '';
    
    formData = {};
    currentStep = 1;
    
    componentHandler.upgradeDom();
}