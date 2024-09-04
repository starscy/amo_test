<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             Данные  {{ isset($user) ? $user : 'пользователя'  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="user-data"></div>
            </div>
        </div>
    </div>
    <script>
        async function collectUserData() {
            try {
                // Получаем IP-адрес и геолокацию
                const response = await fetch('https://ipinfo.io/?token=a988d6a247f215');
                const data = await response.json();

                console.log('data', data)
                // Собираем необходимые данные
                const userData = {
                    ip: data.ip,
                    city: data.city,
                    device: navigator.userAgent
                };

                // Отправляем данные на сервер
                await sendDataToServer(userData);

                // Выводим данные на страницу
                document.getElementById('user-data').innerHTML = `
                <p><strong>IP:</strong> ${userData.ip}</p>
                <p><strong>City:</strong> ${userData.city}</p>
                <p><strong>Device:</strong> ${userData.device}</p>
            `;
            } catch (error) {
                console.error('Ошибка при сборе данных:', error);
            }
        }

        // Функция для отправки данных на сервер
        async function sendDataToServer(data) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log(data)
                await fetch('/dashboard', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                console.log('Данные успешно отправлены на сервер');
            } catch (error) {
                console.error('Ошибка при отправке данных на сервер:', error);
            }
        }

        window.onload = collectUserData;
    </script>

    <canvas id="visitsChart"></canvas>
    <canvas id="citiesChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        fetch('/api/visits-data')
            .then(response => response.json())
            .then(data => {
                console.log('data111', data)
                const visitCounts = data.visits.map(v => v.count);
                const hours = data.visits.map(v => v.hour);
                const cityCounts = data.cities.map(c => c.count);
                const cities = data.cities.map(c => c.city);

                // График посещений
                const ctxVisits = document.getElementById('visitsChart').getContext('2d');
                new Chart(ctxVisits, {
                    type: 'bar',
                    data: {
                        labels: hours,
                        datasets: [{
                            label: 'Количество уникальных посещений',
                            data: visitCounts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Круговая диаграмма по городам
                const ctxCities = document.getElementById('citiesChart').getContext('2d');
                new Chart(ctxCities, {
                    type: 'pie',
                    data: {
                        labels: cities,
                        datasets: [{
                            label: 'Посещения по городам',
                            data: cityCounts,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        }]
                    }
                });
            });
    </script>

</x-app-layout>


