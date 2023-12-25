use std::sync::{atomic::{AtomicUsize, Ordering}, Arc, Mutex, Condvar};
use std::io::{self, Write, Error};
use signal_hook::{iterator::Signals, consts::SIGTERM};
use rustc_version::{version};

fn main() -> Result<(), Error> {
    let count_loops = Arc::new(AtomicUsize::new(0));
    let count_loops_clone = Arc::clone(&count_loops);

    let pair = Arc::new((Mutex::new(false), Condvar::new()));
    let pair2 = Arc::clone(&pair);

    let mut signals = Signals::new(&[SIGTERM])?;

    std::thread::spawn(move || {
        for _ in signals.forever() {
            println!("Rust {} executed the loop {} times before termination.",
                     version().unwrap(),
                     count_loops_clone.load(Ordering::Relaxed));
            io::stdout().flush().unwrap();

            let (lock, cvar) = &*pair2;
            let mut started = lock.lock().unwrap();
            *started = true;
            cvar.notify_one();
        }
    });

    loop {
        count_loops.fetch_add(1, Ordering::Relaxed);

        let (lock, cvar) = &*pair;
        let started = lock.lock().unwrap();
        if *started {
            let _guard = cvar.wait(started).unwrap();
            break;
        }
    }

    Ok(())
}