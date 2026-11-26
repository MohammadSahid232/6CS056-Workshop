<x-layouts.app title="Access Denied">
    <div style="text-align: center; padding: 60px 20px;">
        <h1 style="font-size: 4rem; margin-bottom: 0;">403</h1>
        <h2>Access Denied</h2>
        <p>You do not have permission to access this resource.<br>
           You can only view, edit, or delete courses that <strong>you created</strong>.</p>
        <a href="{{ route('courses.index') }}">← Back to My Courses</a>
    </div>
</x-layouts.app>
