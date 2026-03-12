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

        function checkIfActive(isActive) {
            if (isActive) {
                alert("The scheduled item is already active");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('type').addEventListener('change', toggleFields);
            toggleFields();
        });

        function openEdit(id, title, content, url, schedule, type) {
            document.getElementById('edit-form').action = `/form/${id}/edit`;
            document.getElementById('edit-title').value = title;

            document.getElementById('edit-content').value = content;
            document.getElementById('edit-url').value = url;
            document.getElementById('edit-schedule').value = schedule.split(' ')[0];
            document.getElementById('edit-modal').style.display = 'block';
            toggleEditFields(type);
        }

        function toggleEditFields(type) {
            const contentRow = document.getElementById('edit-content-row');
            const urlRow = document.getElementById('edit-url-row');
            if (type === 'announcement') {
                contentRow.style.display = 'block';
                urlRow.style.display = 'none';
            } else {
                contentRow.style.display = 'none';
                urlRow.style.display = 'block';
            }
        }

        function closeEdit() {
            document.getElementById('edit-modal').style.display = 'none';
        }
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
                    <colgroup>
                        <col class="col-id">
                        <col class="col-title">
                        <col class="col-main">
                        <col class="col-date">
                        <col class="col-status">
                        <col class="col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Ann. ID</th>
                            <th>Ann. Title</th>
                            <th>Content</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $data)
                        @if($data->type === 'announcement')
                        <tr>
                            <td>{{ $data->announcement_id ?? '-' }}</td>
                            <td style="font-weight: bold;">{{ $data->announcement->title ?? '-' }}</td>
                            <td>{{ $data->announcement->content ?? '-' }}</td>
                            <td>{{ $data->schedule ?? '-' }}</td>
                            <td>{{ $data->is_active ? 'Active' : 'Not Active' }}</td>
                            <td>
                                <form action="/form/{{ $data->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="1">
                                    <button class="activate-btn" type="submit" onclick="return checkIfActive({{ $data->is_active ? 'true' : 'false' }})">Activate</button>
                                </form>
                                <br>
                                <button class="edit-btn" type="button" onclick="openEdit(
                                        {{ $data->id }},
                                        '{{ $data->announcement->title ?? '' }}',
                                        '{{ $data->announcement->content ?? '' }}',
                                        ' ',
                                        '{{ $data->schedule }}',
                                        '{{ $data->type }}'
                                    )">Edit</button>
                                <br><br>
                                <form action="/form/{{ $data->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                    <button class="deactivate-btn" type="submit">Deactivate</button>
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
                    <colgroup>
                        <col class="col-id">
                        <col class="col-title">
                        <col class="col-main">
                        <col class="col-date">
                        <col class="col-status">
                        <col class="col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Link ID</th>
                            <th>Link Title</th>
                            <th>URL</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($schedules as $data)
                        @if($data->type === 'link')
                        <tr>
                            <td>{{ $data->link_id ?? '-' }}</td>
                            <td style="font-weight: bold;">{{ $data->link->title ?? '-' }}</td>
                            <td>{{ $data->link->url ?? '-' }}</td>
                            <td>{{ $data->schedule ?? '-'}}</td>
                            <td>{{ $data->is_active ? 'Active' : 'Not Active' }}</td>
                            <td>
                                <form action="/form/{{ $data->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="1">
                                    <button class="activate-btn" type="submit" onclick="return checkIfActive({{ $data->is_active ? 'true' : 'false' }})">Activate</button>
                                </form>
                                <br>
                                <button class="edit-btn" type="button" onclick="openEdit(
                                        {{ $data->id }},
                                        '{{ $data->link->title ?? '' }}',
                                        '',
                                        '{{ $data->link->url ?? '' }}',
                                        '{{ $data->schedule }}',
                                        '{{ $data->type }}'
                                    )">Edit</button>
                                <br><br>
                                <form action="/form/{{ $data->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                    <button class="deactivate-btn" type="submit">Deactivate</button>
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

    <div id="edit-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999;">
        <div style="background:white; padding:24px; border-radius:10px; width:400px; margin:100px auto;">
            <h3>Edit</h3><br>
            <form id="edit-form" method="POST">
                @csrf
                @method('PATCH')

                <label>Title</label>
                <input type="text" name="title" id="edit-title" required><br><br>

                <div id="edit-content-row">
                    <label>Content</label>
                    <input type="text" name="content" id="edit-content"><br><br>
                </div>

                <div id="edit-url-row">
                    <label>URL</label>
                    <input type="text" name="url" id="edit-url"><br><br>
                </div>

                <label>Schedule Date</label>
                <input type="date" name="schedule" id="edit-schedule" required><br><br>

                <button type="submit" class="submit-main">Save Changes</button><br><br>
                <button type="button" onclick="closeEdit()" class="cancel-btn">Cancel</button>
            </form>
        </div>
    </div>

</body>

</html>