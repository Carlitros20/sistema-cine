<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Room;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Ticket;
use App\Models\Rating;

/**
 * Seeder principal del Sistema de Gestión de Cine.
 *
 * Rellena la base de datos con datos de prueba realistas para que
 * cualquier persona que clone el repositorio pueda probar la aplicación
 * inmediatamente sin tener que crear nada manualmente.
 *
 * Uso:
 *   php artisan migrate:fresh --seed
 *   php artisan db:seed --class=CineSeeder
 */
class CineSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Limpieza previa (idempotente) ────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['ratings','favorites','tickets','seat_locks','movie_sessions','movies','rooms','users'] as $t) {
            if (Schema::hasTable($t)) {
                DB::table($t)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ─── Usuarios ────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@cine.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $cliente = User::create([
            'name'     => 'Carlos Luis Mario',
            'email'    => 'cliente@cine.com',
            'password' => Hash::make('user1234'),
            'role'     => 'customer',
        ]);

        $cliente2 = User::create([
            'name'     => 'Maestro',
            'email'    => 'maestro@cine.com',
            'password' => Hash::make('maestro123'),
            'role'     => 'customer',
        ]);

        // ─── Salas ───────────────────────────────────────────────────────
        $salaImax  = Room::create(['name' => 'Sala IMAX',  'capacity' => 100]);
        $sala3D    = Room::create(['name' => 'Sala 3D',    'capacity' => 80]);
        $salaAtmos = Room::create(['name' => 'Sala Atmos', 'capacity' => 75]);

        // ─── Películas (20 títulos con pósters reales de TMDB) ───────────
        $peliculas = [
            [
                'title' => 'Origen',
                'description' => 'Un ladrón especializado en robar secretos durante el sueño recibe el encargo inverso: implantar una idea en la mente de un magnate. Una odisea por niveles oníricos donde el tiempo y la realidad se distorsionan.',
                'duration_minutes' => 148,
                'category' => 'Ciencia Ficción',
                'poster' => 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg',
            ],
            [
                'title' => 'Interstellar',
                'description' => 'En un futuro próximo, la Tierra agoniza y un grupo de astronautas atraviesa un agujero de gusano en busca de un nuevo hogar para la humanidad.',
                'duration_minutes' => 169,
                'category' => 'Ciencia Ficción',
                'poster' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
            ],
            [
                'title' => 'The Matrix',
                'description' => 'Un programador descubre que la realidad en la que vive es una simulación y se une a una rebelión contra las máquinas que controlan a la humanidad.',
                'duration_minutes' => 136,
                'category' => 'Ciencia Ficción',
                'poster' => 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
            ],
            [
                'title' => 'El Caballero Oscuro',
                'description' => 'Batman se enfrenta a su mayor reto: el Joker, un criminal que busca sumir Gotham en el caos. Un duelo psicológico que pondrá a prueba los principios del héroe murciélago.',
                'duration_minutes' => 152,
                'category' => 'Acción',
                'poster' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
            ],
            [
                'title' => 'Parásitos',
                'description' => 'Una familia humilde se infiltra poco a poco en la vida de una familia rica, hasta que un incidente inesperado revela secretos oscuros. Crítica social envuelta en un thriller imprevisible.',
                'duration_minutes' => 132,
                'category' => 'Thriller',
                'poster' => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
            ],
            [
                'title' => 'Coco',
                'description' => 'Miguel sueña con ser músico a pesar de la prohibición de su familia. En el Día de los Muertos termina viajando al mundo de los difuntos, donde descubre la verdad sobre su tatarabuelo.',
                'duration_minutes' => 105,
                'category' => 'Animación',
                'poster' => 'https://image.tmdb.org/t/p/w500/eKi8dIrr8voobbaGzDpe8w0PVbC.jpg',
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Tras ser desenmascarado, Peter Parker pide ayuda al Doctor Strange para volver al anonimato. El hechizo sale mal y trae al universo a villanos de otras dimensiones.',
                'duration_minutes' => 148,
                'category' => 'Aventura',
                'poster' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
            ],
            [
                'title' => 'Dune',
                'description' => 'Paul Atreides debe viajar al planeta más peligroso del universo para asegurar el futuro de su familia y de su pueblo. Una epopeya visualmente deslumbrante sobre poder, profecía y destino.',
                'duration_minutes' => 155,
                'category' => 'Ciencia Ficción',
                'poster' => 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
            ],
            [
                'title' => 'La La Land',
                'description' => 'Un pianista de jazz y una aspirante a actriz se enamoran mientras persiguen sus sueños en Los Ángeles. Un musical contemporáneo lleno de color, música y melancolía.',
                'duration_minutes' => 128,
                'category' => 'Romance',
                'poster' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
            ],
            [
                'title' => 'El Padrino',
                'description' => 'La saga de la familia Corleone, una de las más poderosas dinastías de la mafia ítalo-americana. Un retrato del poder, la lealtad y la traición.',
                'duration_minutes' => 175,
                'category' => 'Drama',
                'poster' => 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
            ],
            [
                'title' => 'Pulp Fiction',
                'description' => 'Las vidas entrelazadas de dos sicarios, un boxeador, un gánster y su esposa, en una narrativa no lineal que reinventó el cine de los 90.',
                'duration_minutes' => 154,
                'category' => 'Drama',
                'poster' => 'https://image.tmdb.org/t/p/w500/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
            ],
            [
                'title' => 'Forrest Gump',
                'description' => 'La extraordinaria vida de un hombre humilde y de buen corazón que, sin pretenderlo, vive de cerca los grandes acontecimientos de la historia americana del siglo XX.',
                'duration_minutes' => 142,
                'category' => 'Drama',
                'poster' => 'https://image.tmdb.org/t/p/w500/saHP97rTPS5eLmrLQEcANmKrsFl.jpg',
            ],
            [
                'title' => 'El Rey León',
                'description' => 'Simba, un joven león, debe asumir su lugar como rey de la sabana tras la trágica muerte de su padre. Una historia épica sobre el ciclo de la vida y el coraje.',
                'duration_minutes' => 88,
                'category' => 'Animación',
                'poster' => 'https://image.tmdb.org/t/p/w500/sKCr78MXSLixwmZ8DyJLrpMsd15.jpg',
            ],
            [
                'title' => 'Gladiator',
                'description' => 'Un general romano traicionado y reducido a esclavo lucha por su libertad en la arena del Coliseo, mientras planea su venganza contra el emperador corrupto.',
                'duration_minutes' => 155,
                'category' => 'Acción',
                'poster' => 'https://image.tmdb.org/t/p/w500/ehGpN04mLJIrSnxcZBMvHeG0eDc.jpg',
            ],
            [
                'title' => 'El Resplandor',
                'description' => 'Un escritor acepta el trabajo de cuidador de un hotel aislado en las montañas durante el invierno. La soledad y las fuerzas sobrenaturales lo empujan hacia la locura.',
                'duration_minutes' => 146,
                'category' => 'Terror',
                'poster' => 'https://image.tmdb.org/t/p/w500/b6ko0IKC8MdYBBPkkA1aBPLe2yz.jpg',
            ],
            [
                'title' => 'Joker',
                'description' => 'Un cómico fracasado de Gotham, marginado por la sociedad, desciende lentamente hacia la locura y se convierte en el icónico villano. Un retrato psicológico inquietante.',
                'duration_minutes' => 122,
                'category' => 'Drama',
                'poster' => 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg',
            ],
            [
                'title' => 'Toy Story',
                'description' => 'Woody, un vaquero de juguete, ve amenazado su lugar como favorito de su dueño con la llegada de Buzz Lightyear, un astronauta espacial. Una aventura de amistad y celos.',
                'duration_minutes' => 81,
                'category' => 'Animación',
                'poster' => 'https://image.tmdb.org/t/p/w500/uXDfjJbdP4ijW5hWSBrPrlKpxab.jpg',
            ],
            [
                'title' => 'Vengadores: Endgame',
                'description' => 'Tras los devastadores eventos de Infinity War, los Vengadores se reúnen una última vez para revertir las acciones de Thanos y restaurar el equilibrio del universo.',
                'duration_minutes' => 181,
                'category' => 'Aventura',
                'poster' => 'https://image.tmdb.org/t/p/w500/ulzhLuWrPK07P1YkdWQLZnQh1JL.jpg',
            ],
            [
                'title' => 'It',
                'description' => 'Un grupo de niños del pequeño pueblo de Derry se enfrenta a un ser maligno que aterroriza a los habitantes y adopta la forma de un payaso llamado Pennywise.',
                'duration_minutes' => 135,
                'category' => 'Terror',
                'poster' => 'https://image.tmdb.org/t/p/w500/9E2y5Q7WlCVNEhP5GiVTjhEhx1o.jpg',
            ],
            [
                'title' => 'Mad Max: Furia en la Carretera',
                'description' => 'En un mundo postapocalíptico, Max se une a Furiosa, una guerrera que lidera una rebelión contra un tirano. Una persecución frenética en el desierto.',
                'duration_minutes' => 120,
                'category' => 'Acción',
                'poster' => 'https://image.tmdb.org/t/p/w500/hA2ple9q4qnwxp3hKVNhroipsir.jpg',
            ],
        ];

        $movies = [];
        foreach ($peliculas as $data) {
            $movies[] = Movie::create($data);
        }

        // ─── Sesiones para los próximos 7 días ───────────────────────────
        // 3 sesiones diarias (tarde, noche, tarde-noche) en cada sala
        $hoy = Carbon::now()->startOfDay();
        $horarios = [
            ['hora' => 17, 'min' => 0],
            ['hora' => 19, 'min' => 30],
            ['hora' => 22, 'min' => 0],
        ];
        $salas = [$salaImax, $sala3D, $salaAtmos];

        $movieIdx = 0;
        for ($dia = 0; $dia < 7; $dia++) {
            foreach ($salas as $sala) {
                foreach ($horarios as $h) {
                    $movie = $movies[$movieIdx % count($movies)];
                    $movieIdx++;

                    $start = $hoy->copy()
                        ->addDays($dia)
                        ->setTime($h['hora'], $h['min']);

                    // Solo creamos sesiones futuras
                    if ($start->isPast()) continue;

                    MovieSession::create([
                        'movie_id'   => $movie->id,
                        'room_id'    => $sala->id,
                        'start_time' => $start,
                    ]);
                }
            }
        }

        // ─── Valoraciones de prueba ──────────────────────────────────────
        $valoraciones = [
            [$movies[0]->id, $cliente->id,  5, '¡Una obra maestra! Cada visionado descubres algo nuevo.'],
            [$movies[0]->id, $cliente2->id, 4, 'Muy buena, pero a veces es difícil de seguir.'],
            [$movies[1]->id, $cliente->id,  5, 'Interstellar te deja pensando durante días. Imprescindible.'],
            [$movies[1]->id, $cliente2->id, 5, 'La mejor película de ciencia ficción de los últimos años.'],
            [$movies[2]->id, $cliente->id,  5, 'Un clásico moderno. Los efectos especiales siguen siendo impactantes.'],
            [$movies[3]->id, $cliente->id,  5, 'Heath Ledger crea el mejor villano de la historia del cine.'],
            [$movies[4]->id, $cliente2->id, 5, 'Merecidísimo el Oscar a mejor película.'],
            [$movies[5]->id, $cliente->id,  4, 'Una historia preciosa y muy emotiva, perfecta para toda la familia.'],
            [$movies[7]->id, $cliente2->id, 4, 'Visualmente espectacular, una experiencia de cine total.'],
            [$movies[8]->id, $cliente->id,  4, 'Banda sonora maravillosa y química entre los protagonistas.'],
            [$movies[9]->id, $cliente2->id, 5, 'Marlon Brando está sublime. Una pieza fundamental del cine.'],
            [$movies[15]->id, $cliente->id, 4, 'Joaquin Phoenix se merece todos los premios por esta interpretación.'],
        ];

        foreach ($valoraciones as [$movieId, $userId, $score, $comment]) {
            Rating::create([
                'movie_id' => $movieId,
                'user_id'  => $userId,
                'score'    => $score,
                'comment'  => $comment,
            ]);
        }

        // ─── Entradas compradas (para que las estadísticas tengan datos) ──
        $sesiones = MovieSession::orderBy('start_time')->take(6)->get();
        foreach ($sesiones as $i => $sesion) {
            Ticket::create([
                'user_id'          => $cliente->id,
                'movie_session_id' => $sesion->id,
                'seat'             => 25 + $i,
            ]);
            Ticket::create([
                'user_id'          => $cliente2->id,
                'movie_session_id' => $sesion->id,
                'seat'             => 30 + $i,
            ]);
        }

        // ─── Favoritos del cliente ───────────────────────────────────────
        $cliente->favorites()->attach([
            $movies[0]->id,
            $movies[1]->id,
            $movies[2]->id,
            $movies[5]->id,
        ]);

        $cliente2->favorites()->attach([
            $movies[8]->id,
            $movies[9]->id,
            $movies[12]->id,
        ]);

        // ─── Resumen por consola ─────────────────────────────────────────
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('  Cine CLM — Datos de prueba cargados con éxito');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('  Usuarios:    3 (1 admin, 2 clientes)');
        $this->command->info('  Salas:       3');
        $this->command->info('  Películas:   ' . count($peliculas));
        $this->command->info('  Sesiones:    ' . MovieSession::count());
        $this->command->info('  Entradas:    ' . Ticket::count());
        $this->command->info('  Ratings:     ' . Rating::count());
        $this->command->info('');
        $this->command->info('  Acceso administrador → admin@cine.com / admin123');
        $this->command->info('  Acceso cliente 1     → cliente@cine.com / user1234');
        $this->command->info('  Acceso cliente 2     → maestro@cine.com / maestro123');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}
