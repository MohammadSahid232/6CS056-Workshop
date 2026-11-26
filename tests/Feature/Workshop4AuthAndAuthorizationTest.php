<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Workshop4AuthAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_default_user_role_and_hashed_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Alice Staff',
            'email' => 'alice@institute.test',
            'password' => 'SecretPass123',
            'password_confirmation' => 'SecretPass123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertAuthenticated();

        $user = User::where('email', 'alice@institute.test')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertNotSame('SecretPass123', $user->password);
        $this->assertTrue(Hash::check('SecretPass123', $user->password));
    }

    public function test_registration_validation_rejects_invalid_inputs(): void
    {
        User::factory()->create(['email' => 'taken@institute.test']);

        $this->from('/register')
            ->post('/register', [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
                'password_confirmation' => 'mismatch',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->from('/register')
            ->post('/register', [
                'name' => 'Duplicate User',
                'email' => 'taken@institute.test',
                'password' => 'SecretPass123',
                'password_confirmation' => 'SecretPass123',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_can_login_and_session_is_regenerated(): void
    {
        $user = User::factory()->create([
            'email' => 'staff@institute.test',
            'password' => 'SecretPass123',
        ]);

        $this->get('/login')->assertOk();
        $oldSessionId = session()->getId();

        $response = $this->post('/login', [
            'email' => 'staff@institute.test',
            'password' => 'SecretPass123',
        ]);

        $response->assertRedirect(route('courses.index'))
            ->assertSessionHas('success', 'Login successful!');
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($oldSessionId, session()->getId());

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Login successful!');
    }

    public function test_invalid_login_is_rejected_without_revealing_user_existence(): void
    {
        User::factory()->create([
            'email' => 'staff@institute.test',
            'password' => 'SecretPass123',
        ]);

        $wrongPassword = $this->from('/login')
            ->post('/login', [
                'email' => 'staff@institute.test',
                'password' => 'WrongPassword',
            ]);

        $wrongPassword->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $wrongEmail = $this->from('/login')
            ->post('/login', [
                'email' => 'unknown@institute.test',
                'password' => 'WrongPassword',
            ]);

        $wrongEmail->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertSame(
            session('errors')->first('email'),
            'The provided credentials do not match our records.'
        );
        $this->assertGuest();
    }

    public function test_unauthenticated_users_cannot_access_courses_or_students(): void
    {
        $owner = User::factory()->create();
        $course = $this->createCourseFor($owner);
        $student = $this->createStudent();

        $this->get(route('courses.index'))->assertRedirect(route('login'));
        $this->get(route('courses.create'))->assertRedirect(route('login'));
        $this->post(route('courses.store'), [])->assertRedirect(route('login'));
        $this->get(route('courses.show', $course))->assertRedirect(route('login'));
        $this->get(route('courses.edit', $course))->assertRedirect(route('login'));
        $this->put(route('courses.update', $course), [])->assertRedirect(route('login'));
        $this->delete(route('courses.destroy', $course))->assertRedirect(route('login'));

        $this->get(route('students.index'))->assertRedirect(route('login'));
        $this->get(route('students.create'))->assertRedirect(route('login'));
        $this->post(route('students.store'), [])->assertRedirect(route('login'));
        $this->get(route('students.show', $student))->assertRedirect(route('login'));
        $this->get(route('students.edit', $student))->assertRedirect(route('login'));
        $this->put(route('students.update', $student), [])->assertRedirect(route('login'));
        $this->delete(route('students.destroy', $student))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_logout_and_loses_access_to_protected_pages(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldToken = csrf_token();

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertNotSame($oldToken, csrf_token());

        $this->get(route('courses.index'))->assertRedirect(route('login'));
        $this->get(route('students.index'))->assertRedirect(route('login'));
    }

    public function test_navbar_displays_appropriate_links_for_guests_and_authenticated_users(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Register')
            ->assertSee('Login')
            ->assertDontSee('Log Out');

        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Courses')
            ->assertSee('Students')
            ->assertSee('Logged in as user')
            ->assertSee('Log Out');

        $this->actingAs($admin)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Logged in as admin');
    }

    public function test_creating_course_assigns_authenticated_user_and_ignores_submitted_user_id(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA)
            ->post(route('courses.store'), [
                'name' => 'Laravel Security',
                'description' => 'Gates and auth',
                'duration' => 6,
                'fee' => 500,
                'difficulty' => 'Medium',
                'is_active' => '1',
                'user_id' => $userB->id,
            ])
            ->assertRedirect(route('courses.index'));

        $this->assertDatabaseHas('courses', [
            'name' => 'Laravel Security',
            'user_id' => $userA->id,
        ]);
    }

    public function test_course_owner_isolation_and_gate_authorization(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $courseA = $this->createCourseFor($userA, 'User A Course');
        $courseB = $this->createCourseFor($userB, 'User B Course');

        $this->assertTrue(Gate::forUser($userA)->allows('manage-course', $courseA));
        $this->assertFalse(Gate::forUser($userB)->allows('manage-course', $courseA));

        $this->actingAs($userA)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertSee('User A Course')
            ->assertDontSee('User B Course');

        $this->actingAs($userA)->get(route('courses.show', $courseA))->assertOk();
        $this->actingAs($userA)->get(route('courses.edit', $courseA))->assertOk();

        $this->actingAs($userB)->get(route('courses.show', $courseA))->assertForbidden();
        $this->actingAs($userB)->get(route('courses.edit', $courseA))->assertForbidden();
        $this->actingAs($userB)->put(route('courses.update', $courseA), [
            'name' => 'Hacked Course',
            'description' => 'Updated',
            'duration' => 4,
            'fee' => 200,
            'difficulty' => 'Easy',
            'is_active' => '1',
        ])->assertForbidden();
        $this->actingAs($userB)->delete(route('courses.destroy', $courseA))->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'id' => $courseA->id,
            'name' => 'User A Course',
        ]);
    }

    public function test_admin_seeder_and_admin_full_access_to_all_courses_and_students(): void
    {
        $this->seed(AdminSeeder::class);
        $this->seed(AdminSeeder::class);

        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('Admin@123', $admin->password));
        $this->assertSame(1, User::where('email', 'admin@example.com')->count());

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseA = $this->createCourseFor($userA, 'Alpha Course');
        $courseB = $this->createCourseFor($userB, 'Beta Course');

        $this->assertTrue(Gate::forUser($admin)->allows('manage-course', $courseA));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-course', $courseB));

        $this->actingAs($admin)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Alpha Course')
            ->assertSee('Beta Course');

        $this->actingAs($admin)->get(route('courses.show', $courseA))->assertOk();
        $this->actingAs($admin)->get(route('courses.edit', $courseA))->assertOk();
        $this->actingAs($admin)->put(route('courses.update', $courseA), [
            'name' => 'Updated By Admin',
            'description' => 'Admin edit',
            'duration' => 10,
            'fee' => 999,
            'difficulty' => 'Hard',
            'is_active' => '1',
        ])->assertRedirect(route('courses.show', $courseA));

        $this->assertDatabaseHas('courses', [
            'id' => $courseA->id,
            'name' => 'Updated By Admin',
        ]);

        $this->actingAs($admin)
            ->delete(route('courses.destroy', $courseB))
            ->assertRedirect(route('courses.index'));

        $this->assertDatabaseMissing('courses', ['id' => $courseB->id]);
    }

    public function test_students_are_shared_across_all_authenticated_users(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $student = $this->createStudent();

        $this->actingAs($userA)->get(route('students.index'))->assertOk()->assertSee($student->name);
        $this->actingAs($userB)->get(route('students.show', $student))->assertOk();
        $this->actingAs($userB)->get(route('students.edit', $student))->assertOk();
    }

    private function createCourseFor(User $user, string $name = 'Sample Course'): Course
    {
        return $user->courses()->create([
            'name' => $name,
            'description' => 'Course description',
            'duration' => 8,
            'fee' => 500.00,
            'difficulty' => 'Medium',
            'is_active' => true,
        ]);
    }

    private function createStudent(): Student
    {
        return Student::create([
            'name' => 'Shared Student',
            'email' => 'shared.student@example.com',
            'phone' => '555-0199',
            'address' => '100 Institute Way',
            'date_of_birth' => '2005-01-01',
        ]);
    }
}
