<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Link Scheduler</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            const contentRow = document.getElementById('content-row');
            const urlRow = document.getElementById('url-row');
            const contentInput = document.getElementById('content');
            const urlInput = document.getElementById('url');

            if (type === 'announcement') {
                contentRow.style.display = 'flex';
                urlRow.style.display = 'none';
                contentInput.required = true;
                urlInput.required = false;
            } else {
                contentRow.style.display = 'none';
                urlRow.style.display = 'flex';
                contentInput.required = false;
                urlInput.required = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('type').addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
</head>

<body>

    <header class="page-header">
        <h1>Link Scheduling System</h1>
    </header>

    @include('partials.alerts')

    <div class="container">

        <div class="card form-card">
            <h2>Create Schedule</h2>

            <form action="/form" method="POST" class="form-grid">
                @csrf

                <div>
                    <label for="type">Type</label>
                    <select name="type" id="type">
                        <option value="announcement">Announcement</option>
                        <option value="link">Link</option>
                    </select>
                </div>

                <div>
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div id="content-row">
                    <label for="content">Content</label>
                    <input type="text" id="content" name="content">
                </div>

                <div id="url-row">
                    <label for="url">URL</label>
                    <input type="text" id="url" name="url">
                </div>

                <div>
                    <label for="schedule">Schedule Date</label>
                    <input type="date" id="schedule" name="schedule" min="{{ date('Y-m-d') }}" required>
                </div>

                <input type="hidden" name="is_active" value="1">

                <button type="submit" class="submit-main">Create Schedule</button>

            </form>
        </div>

        <div class="card table-card">
            <h2>Scheduled Items</h2>

            <div class="table-wrapper">
                <h5>Announcements</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Ann. ID</th>
                            <th>Ann. Title</th>
                            <th>Content</th>
                            <th>Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($schedules as $data)
                        @if($data->type === 'announcement')
                        <tr>
                            <td>{{ $data->announcement_id ?? '-' }}</td>
                            <td>{{ $data->announcement->title ?? '-' }}</td>
                            <td>{{ $data->announcement->content ?? '-' }}</td>
                            <td>{{ $data->schedule ?? '-'}}</td>
                            <td>
                                <form action="/form/{{ $data->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                    <button class="remove-btn" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br>
            <div class="table-wrapper">
                <h5>Links</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Link ID</th>
                            <th>Link Title</th>
                            <th>URL</th>
                            <th>Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($schedules as $data)
                        @if($data->type === 'link')
                        <tr>
                            <td>{{ $data->link_id ?? '-' }}</td>
                            <td>{{ $data->link->title ?? '-' }}</td>
                            <td>{{ $data->link->url ?? '-' }}</td>
                            <td>{{ $data->schedule ?? '-'}}</td>
                            <td>
                                <form action="/form/{{ $data->link_id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                    <button class="remove-btn" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>


    </div>

</body>

</html>